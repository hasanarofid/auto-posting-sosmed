const express = require('express');
const router = express.Router();
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

const FACEBOOK_GRAPH_API = 'https://graph.facebook.com';
const FACEBOOK_API_VERSION = 'v18.0';

// Post ke Facebook Page
router.post('/post', async (req, res) => {
  try {
    const { 
      accessToken, 
      pageId, 
      message, 
      imageUrl 
    } = req.body;
    
    if (!accessToken || !pageId || !message) {
      return res.status(400).json({ 
        error: 'accessToken, pageId, dan message diperlukan' 
      });
    }
    
    let postData = {
      message: message,
      access_token: accessToken
    };
    
    // Jika ada gambar, upload dulu
    if (imageUrl) {
      // Upload photo ke Facebook
      const photoResponse = await axios.post(
        `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/${pageId}/photos`,
        {
          url: imageUrl,
          caption: message,
          access_token: accessToken
        }
      );
      
      res.json({
        success: true,
        postId: photoResponse.data.id,
        message: 'Post berhasil diupload ke Facebook'
      });
    } else {
      // Post text saja
      const postResponse = await axios.post(
        `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/${pageId}/feed`,
        postData
      );
      
      res.json({
        success: true,
        postId: postResponse.data.id,
        message: 'Post berhasil diupload ke Facebook'
      });
    }
    
  } catch (error) {
    console.error('Facebook API Error:', error.response?.data || error.message);
    res.status(500).json({ 
      error: 'Gagal posting ke Facebook',
      details: error.response?.data || error.message 
    });
  }
});

// Get Facebook Page info
router.get('/page', async (req, res) => {
  try {
    const { accessToken, pageId } = req.query;
    
    if (!accessToken || !pageId) {
      return res.status(400).json({ error: 'accessToken dan pageId diperlukan' });
    }
    
    const response = await axios.get(
      `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/${pageId}`,
      {
        params: {
          fields: 'id,name,access_token',
          access_token: accessToken
        }
      }
    );
    
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ 
      error: 'Gagal mendapatkan info page',
      details: error.response?.data || error.message 
    });
  }
});

// Get list of pages
router.get('/pages', async (req, res) => {
  try {
    const { accessToken } = req.query;
    
    if (!accessToken) {
      return res.status(400).json({ error: 'accessToken diperlukan' });
    }
    
    const response = await axios.get(
      `${FACEBOOK_GRAPH_API}/${FACEBOOK_API_VERSION}/me/accounts`,
      {
        params: {
          access_token: accessToken
        }
      }
    );
    
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ 
      error: 'Gagal mendapatkan list pages',
      details: error.response?.data || error.message 
    });
  }
});

// Test connection
router.get('/test', (req, res) => {
  res.json({ 
    message: 'Facebook API endpoint aktif',
    note: 'Gunakan Facebook Graph API untuk posting. Perlu setup di Facebook Developer Console.'
  });
});

module.exports = router;

