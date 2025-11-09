const express = require('express');
const router = express.Router();
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');
const path = require('path');

// Instagram API Configuration
const INSTAGRAM_GRAPH_API = 'https://graph.instagram.com';
const INSTAGRAM_API_VERSION = 'v18.0';

// Helper function untuk post ke Instagram
router.post('/post', async (req, res) => {
  try {
    const { accessToken, imageUrl, caption, userId } = req.body;
    
    if (!accessToken || !imageUrl || !caption || !userId) {
      return res.status(400).json({ 
        error: 'accessToken, imageUrl, caption, dan userId diperlukan' 
      });
    }
    
    // Step 1: Upload image dan create media container
    const createMediaResponse = await axios.post(
      `${INSTAGRAM_GRAPH_API}/${INSTAGRAM_API_VERSION}/${userId}/media`,
      {
        image_url: imageUrl,
        caption: caption,
        access_token: accessToken
      }
    );
    
    const creationId = createMediaResponse.data.id;
    
    // Step 2: Publish the media container
    const publishResponse = await axios.post(
      `${INSTAGRAM_GRAPH_API}/${INSTAGRAM_API_VERSION}/${userId}/media_publish`,
      {
        creation_id: creationId,
        access_token: accessToken
      }
    );
    
    res.json({
      success: true,
      mediaId: publishResponse.data.id,
      message: 'Post berhasil diupload ke Instagram'
    });
    
  } catch (error) {
    console.error('Instagram API Error:', error.response?.data || error.message);
    res.status(500).json({ 
      error: 'Gagal posting ke Instagram',
      details: error.response?.data || error.message 
    });
  }
});

// Get Instagram user info
router.get('/user', async (req, res) => {
  try {
    const { accessToken } = req.query;
    
    if (!accessToken) {
      return res.status(400).json({ error: 'accessToken diperlukan' });
    }
    
    const response = await axios.get(
      `${INSTAGRAM_GRAPH_API}/${INSTAGRAM_API_VERSION}/me`,
      {
        params: {
          fields: 'id,username,account_type',
          access_token: accessToken
        }
      }
    );
    
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ 
      error: 'Gagal mendapatkan info user',
      details: error.response?.data || error.message 
    });
  }
});

// Test connection
router.get('/test', (req, res) => {
  res.json({ 
    message: 'Instagram API endpoint aktif',
    note: 'Gunakan Instagram Graph API untuk posting. Perlu setup di Facebook Developer Console.'
  });
});

module.exports = router;

