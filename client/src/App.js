import React, { useState } from 'react';
import './App.css';
import ContentUploader from './components/ContentUploader';
import PlatformSelector from './components/PlatformSelector';
import ContentPreview from './components/ContentPreview';
import PostButton from './components/PostButton';
import axios from 'axios';

function App() {
  const [content, setContent] = useState({
    caption: '',
    image: null,
    imagePreview: null,
    platforms: []
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ type: '', text: '' });

  const handleImageUpload = async (file) => {
    try {
      setLoading(true);
      const formData = new FormData();
      formData.append('image', file);

      const apiUrl = process.env.REACT_APP_API_URL || '/api';
      const response = await axios.post(`${apiUrl}/upload`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      setContent(prev => ({
        ...prev,
        image: response.data.file.filename,
        imagePreview: URL.createObjectURL(file)
      }));

      setMessage({ type: 'success', text: 'Gambar berhasil diupload!' });
    } catch (error) {
      setMessage({ type: 'error', text: 'Gagal upload gambar: ' + error.message });
    } finally {
      setLoading(false);
    }
  };

  const handleCaptionChange = (caption) => {
    setContent(prev => ({ ...prev, caption }));
  };

  const handlePlatformToggle = (platform) => {
    setContent(prev => ({
      ...prev,
      platforms: prev.platforms.includes(platform)
        ? prev.platforms.filter(p => p !== platform)
        : [...prev.platforms, platform]
    }));
  };

  const handlePost = async () => {
    if (!content.caption || !content.image) {
      setMessage({ type: 'error', text: 'Mohon isi caption dan upload gambar terlebih dahulu!' });
      return;
    }

    if (content.platforms.length === 0) {
      setMessage({ type: 'error', text: 'Pilih minimal satu platform!' });
      return;
    }

    try {
      setLoading(true);
      setMessage({ type: '', text: '' });

      const apiUrl = process.env.REACT_APP_API_URL || '/api';
      const baseUrl = process.env.REACT_APP_API_URL 
        ? process.env.REACT_APP_API_URL.replace('/api', '')
        : window.location.origin;

      // Simpan konten dulu
      await axios.post(`${apiUrl}/content`, {
        caption: content.caption,
        image: content.image,
        platforms: content.platforms
      });

      // Post ke setiap platform yang dipilih
      const postPromises = content.platforms.map(platform => {
        const imageUrl = `${baseUrl}/api/uploads/${content.image}`;
        
        switch (platform) {
          case 'facebook':
            return axios.post(`${apiUrl}/facebook/post`, {
              accessToken: localStorage.getItem('facebook_token') || '',
              pageId: localStorage.getItem('facebook_page_id') || '',
              message: content.caption,
              imageUrl: imageUrl
            });
          case 'instagram':
            return axios.post(`${apiUrl}/instagram/post`, {
              accessToken: localStorage.getItem('instagram_token') || '',
              imageUrl: imageUrl,
              caption: content.caption,
              userId: localStorage.getItem('instagram_user_id') || ''
            });
          case 'linkedin':
            return axios.post(`${apiUrl}/linkedin/post`, {
              accessToken: localStorage.getItem('linkedin_token') || '',
              text: content.caption,
              imageUrl: imageUrl
            });
          case 'threads':
            return axios.post(`${apiUrl}/threads/post`, {
              accessToken: localStorage.getItem('threads_token') || '',
              text: content.caption,
              imageUrl: imageUrl,
              appId: localStorage.getItem('threads_app_id') || ''
            });
          case 'tiktok':
            return axios.post(`${apiUrl}/tiktok/post`, {
              accessToken: localStorage.getItem('tiktok_token') || '',
              videoUrl: imageUrl, // TikTok biasanya untuk video
              caption: content.caption,
              openId: localStorage.getItem('tiktok_open_id') || ''
            });
          default:
            return Promise.resolve();
        }
      });

      await Promise.allSettled(postPromises);
      
      setMessage({ type: 'success', text: 'Konten berhasil diposting!' });
      
      // Reset form
      setContent({
        caption: '',
        image: null,
        imagePreview: null,
        platforms: []
      });
    } catch (error) {
      setMessage({ type: 'error', text: 'Gagal posting: ' + error.message });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="App">
      <div className="container">
        <header className="header">
          <h1>📱 Sosmed Auto Poster</h1>
          <p>Upload konten sekali, posting ke semua platform</p>
        </header>

        {message.text && (
          <div className={`message ${message.type}`}>
            {message.text}
          </div>
        )}

        <div className="main-content">
          <div className="upload-section">
            <ContentUploader
              onImageUpload={handleImageUpload}
              onCaptionChange={handleCaptionChange}
              caption={content.caption}
              imagePreview={content.imagePreview}
              loading={loading}
            />
          </div>

          <div className="preview-section">
            <PlatformSelector
              selectedPlatforms={content.platforms}
              onPlatformToggle={handlePlatformToggle}
            />

            <ContentPreview
              caption={content.caption}
              imagePreview={content.imagePreview}
            />

            <PostButton
              onClick={handlePost}
              loading={loading}
              disabled={!content.caption || !content.image || content.platforms.length === 0}
            />
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;

