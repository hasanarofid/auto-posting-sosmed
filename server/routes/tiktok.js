const express = require('express');
const router = express.Router();
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

const TIKTOK_API = 'https://open-api.tiktok.com';

// Post video ke TikTok
router.post('/post', async (req, res) => {
  try {
    const { accessToken, videoUrl, caption, openId } = req.body;
    
    if (!accessToken || !videoUrl || !openId) {
      return res.status(400).json({ 
        error: 'accessToken, videoUrl, dan openId diperlukan' 
      });
    }
    
    // TikTok API untuk upload video
    // Step 1: Initialize upload
    const initResponse = await axios.post(
      `${TIKTOK_API}/share/video/upload/`,
      {
        source_info: {
          source: 'FILE_UPLOAD'
        }
      },
      {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'Content-Type': 'application/json'
        }
      }
    );
    
    const uploadUrl = initResponse.data.data.upload_url;
    const publishId = initResponse.data.data.publish_id;
    
    // Step 2: Download video dari URL dan upload
    const videoResponse = await axios.get(videoUrl, { responseType: 'arraybuffer' });
    
    await axios.put(uploadUrl, videoResponse.data, {
      headers: {
        'Content-Type': 'video/mp4'
      }
    });
    
    // Step 3: Publish video
    const publishResponse = await axios.post(
      `${TIKTOK_API}/share/video/publish/`,
      {
        publish_id: publishId,
        post_info: {
          title: caption || 'Check out this video!',
          privacy_level: 'PUBLIC_TO_EVERYONE',
          disable_duet: false,
          disable_comment: false,
          disable_stitch: false,
          video_cover_timestamp_ms: 1000
        },
        source_info: {
          source: 'FILE_UPLOAD'
        }
      },
      {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'Content-Type': 'application/json'
        }
      }
    );
    
    res.json({
      success: true,
      videoId: publishResponse.data.data.share_id,
      message: 'Video berhasil diupload ke TikTok'
    });
    
  } catch (error) {
    console.error('TikTok API Error:', error.response?.data || error.message);
    res.status(500).json({ 
      error: 'Gagal posting ke TikTok',
      details: error.response?.data || error.message,
      note: 'TikTok API memerlukan setup khusus dan approval. Pastikan sudah mendaftar di TikTok Developer Portal.'
    });
  }
});

// Get TikTok user info
router.get('/user', async (req, res) => {
  try {
    const { accessToken, openId } = req.query;
    
    if (!accessToken || !openId) {
      return res.status(400).json({ error: 'accessToken dan openId diperlukan' });
    }
    
    const response = await axios.get(
      `${TIKTOK_API}/user/info/`,
      {
        params: {
          open_id: openId,
          fields: 'open_id,union_id,avatar_url,display_name'
        },
        headers: {
          'Authorization': `Bearer ${accessToken}`
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
    message: 'TikTok API endpoint aktif',
    note: 'TikTok API memerlukan setup di TikTok Developer Portal dan approval untuk production use.'
  });
});

module.exports = router;

