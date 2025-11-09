const express = require('express');
const router = express.Router();
const axios = require('axios');

const THREADS_API = 'https://graph.threads.net';
const THREADS_API_VERSION = 'v1.0';

// Post ke Threads (Meta Threads API)
router.post('/post', async (req, res) => {
  try {
    const { accessToken, text, imageUrl, appId } = req.body;
    
    if (!accessToken || !text || !appId) {
      return res.status(400).json({ 
        error: 'accessToken, text, dan appId diperlukan' 
      });
    }
    
    // Threads API menggunakan Instagram Graph API
    // Step 1: Create media container
    let mediaData = {
      media_type: imageUrl ? 'IMAGE' : 'TEXT',
      text: text,
      access_token: accessToken
    };
    
    if (imageUrl) {
      mediaData.image_url = imageUrl;
    }
    
    const createMediaResponse = await axios.post(
      `${THREADS_API}/${THREADS_API_VERSION}/${appId}/threads`,
      mediaData
    );
    
    const threadId = createMediaResponse.data.id;
    
    // Step 2: Publish the thread
    const publishResponse = await axios.post(
      `${THREADS_API}/${THREADS_API_VERSION}/${appId}/threads_publish`,
      {
        creation_id: threadId,
        access_token: accessToken
      }
    );
    
    res.json({
      success: true,
      threadId: publishResponse.data.id,
      message: 'Thread berhasil diupload'
    });
    
  } catch (error) {
    console.error('Threads API Error:', error.response?.data || error.message);
    res.status(500).json({ 
      error: 'Gagal posting ke Threads',
      details: error.response?.data || error.message 
    });
  }
});

// Get Threads user info
router.get('/user', async (req, res) => {
  try {
    const { accessToken } = req.query;
    
    if (!accessToken) {
      return res.status(400).json({ error: 'accessToken diperlukan' });
    }
    
    const response = await axios.get(
      `${THREADS_API}/${THREADS_API_VERSION}/me`,
      {
        params: {
          fields: 'id,threads_count',
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
    message: 'Threads API endpoint aktif',
    note: 'Threads menggunakan Meta Threads API. Perlu setup di Meta Developer Console.'
  });
});

module.exports = router;

