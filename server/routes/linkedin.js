const express = require('express');
const router = express.Router();
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

const LINKEDIN_API = 'https://api.linkedin.com/v2';

// Post text content ke LinkedIn
router.post('/post', async (req, res) => {
  try {
    const { accessToken, text, imageUrl } = req.body;
    
    if (!accessToken || !text) {
      return res.status(400).json({ 
        error: 'accessToken dan text diperlukan' 
      });
    }
    
    // Get user profile untuk mendapatkan person URN
    const profileResponse = await axios.get(
      `${LINKEDIN_API}/me`,
      {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'X-Restli-Protocol-Version': '2.0.0'
        }
      }
    );
    
    const personUrn = profileResponse.data.id;
    
    // Prepare post data
    let postData = {
      author: `urn:li:person:${personUrn}`,
      lifecycleState: 'PUBLISHED',
      specificContent: {
        'com.linkedin.ugc.ShareContent': {
          shareCommentary: {
            text: text
          },
          shareMediaCategory: imageUrl ? 'IMAGE' : 'NONE'
        }
      },
      visibility: {
        'com.linkedin.ugc.MemberNetworkVisibility': 'PUBLIC'
      }
    };
    
    // Jika ada gambar, upload dulu
    if (imageUrl) {
      // Upload image ke LinkedIn
      const imageUploadResponse = await axios.post(
        `${LINKEDIN_API}/assets?action=registerUpload`,
        {
          registerUploadRequest: {
            recipes: ['urn:li:digitalmediaRecipe:feedshare-image'],
            owner: `urn:li:person:${personUrn}`,
            serviceRelationships: [{
              relationshipType: 'OWNER',
              identifier: 'urn:li:userGeneratedContent'
            }]
          }
        },
        {
          headers: {
            'Authorization': `Bearer ${accessToken}`,
            'X-Restli-Protocol-Version': '2.0.0',
            'Content-Type': 'application/json'
          }
        }
      );
      
      // Upload actual image file
      const uploadUrl = imageUploadResponse.data.value.uploadMechanism['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest'].uploadUrl;
      const asset = imageUploadResponse.data.value.asset;
      
      // Download image dari URL dan upload
      const imageResponse = await axios.get(imageUrl, { responseType: 'arraybuffer' });
      await axios.put(uploadUrl, imageResponse.data, {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'Content-Type': 'image/jpeg'
        }
      });
      
      postData.specificContent['com.linkedin.ugc.ShareContent'].media = [{
        status: 'READY',
        description: {
          text: text
        },
        media: asset,
        title: {
          text: 'Shared Image'
        }
      }];
    }
    
    // Post ke LinkedIn
    const postResponse = await axios.post(
      `${LINKEDIN_API}/ugcPosts`,
      postData,
      {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'X-Restli-Protocol-Version': '2.0.0',
          'Content-Type': 'application/json'
        }
      }
    );
    
    res.json({
      success: true,
      postId: postResponse.data.id,
      message: 'Post berhasil diupload ke LinkedIn'
    });
    
  } catch (error) {
    console.error('LinkedIn API Error:', error.response?.data || error.message);
    res.status(500).json({ 
      error: 'Gagal posting ke LinkedIn',
      details: error.response?.data || error.message 
    });
  }
});

// Get LinkedIn user profile
router.get('/profile', async (req, res) => {
  try {
    const { accessToken } = req.query;
    
    if (!accessToken) {
      return res.status(400).json({ error: 'accessToken diperlukan' });
    }
    
    const response = await axios.get(
      `${LINKEDIN_API}/me`,
      {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'X-Restli-Protocol-Version': '2.0.0'
        }
      }
    );
    
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ 
      error: 'Gagal mendapatkan profile',
      details: error.response?.data || error.message 
    });
  }
});

// Test connection
router.get('/test', (req, res) => {
  res.json({ 
    message: 'LinkedIn API endpoint aktif',
    note: 'Perlu setup LinkedIn App di LinkedIn Developer Portal dan mendapatkan OAuth token.'
  });
});

module.exports = router;

