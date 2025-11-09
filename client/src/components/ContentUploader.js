import React, { useRef } from 'react';
import './ContentUploader.css';
import { FiImage } from 'react-icons/fi';

const ContentUploader = ({ onImageUpload, onCaptionChange, caption, imagePreview, loading }) => {
  const fileInputRef = useRef(null);

  const handleFileSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
      onImageUpload(file);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
      onImageUpload(file);
    }
  };

  const handleDragOver = (e) => {
    e.preventDefault();
  };

  return (
    <div className="content-uploader">
      <h2>📝 Buat Konten</h2>
      
      <div className="upload-area">
        <div
          className={`drop-zone ${imagePreview ? 'has-image' : ''}`}
          onDrop={handleDrop}
          onDragOver={handleDragOver}
          onClick={() => !imagePreview && fileInputRef.current?.click()}
        >
          {imagePreview ? (
            <div className="image-preview-container">
              <img src={imagePreview} alt="Preview" className="image-preview" />
              <button
                className="change-image-btn"
                onClick={(e) => {
                  e.stopPropagation();
                  fileInputRef.current?.click();
                }}
              >
                Ganti Gambar
              </button>
            </div>
          ) : (
            <div className="upload-placeholder">
              <FiImage className="upload-icon" />
              <p>Klik atau drag & drop gambar di sini</p>
              <span className="upload-hint">Format: JPG, PNG, GIF (Max 10MB)</span>
            </div>
          )}
        </div>
        
        <input
          ref={fileInputRef}
          type="file"
          accept="image/*"
          onChange={handleFileSelect}
          style={{ display: 'none' }}
        />
      </div>

      <div className="caption-section">
        <label htmlFor="caption">Caption / Deskripsi</label>
        <textarea
          id="caption"
          className="caption-input"
          placeholder="Tulis caption untuk postingan Anda..."
          value={caption}
          onChange={(e) => onCaptionChange(e.target.value)}
          rows="6"
          disabled={loading}
        />
        <div className="char-count">{caption.length} karakter</div>
      </div>
    </div>
  );
};

export default ContentUploader;

