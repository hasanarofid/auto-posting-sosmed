import React from 'react';
import './PostButton.css';

const PostButton = ({ onClick, loading, disabled }) => {
  return (
    <button
      className={`post-button ${disabled ? 'disabled' : ''}`}
      onClick={onClick}
      disabled={disabled || loading}
    >
      {loading ? (
        <>
          <span className="spinner"></span>
          Memproses...
        </>
      ) : (
        <>
          🚀 Post ke Semua Platform
        </>
      )}
    </button>
  );
};

export default PostButton;

