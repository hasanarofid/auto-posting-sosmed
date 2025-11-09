import React from 'react';
import './ContentPreview.css';

const ContentPreview = ({ caption, imagePreview }) => {
  if (!caption && !imagePreview) {
    return (
      <div className="content-preview">
        <h2>👁️ Preview</h2>
        <div className="preview-empty">
          <p>Preview akan muncul di sini setelah Anda mengisi konten</p>
        </div>
      </div>
    );
  }

  return (
    <div className="content-preview">
      <h2>👁️ Preview</h2>
      <div className="preview-card">
        {imagePreview && (
          <div className="preview-image">
            <img src={imagePreview} alt="Preview" />
          </div>
        )}
        {caption && (
          <div className="preview-caption">
            {caption}
          </div>
        )}
      </div>
    </div>
  );
};

export default ContentPreview;

