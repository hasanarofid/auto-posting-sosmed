import React from 'react';
import './PlatformSelector.css';
import { FaInstagram, FaLinkedin, FaThreads } from 'react-icons/fa6';
import { FaTiktok, FaFacebook } from 'react-icons/fa';

const platforms = [
  { id: 'facebook', name: 'Facebook', icon: FaFacebook, color: '#1877F2' },
  { id: 'instagram', name: 'Instagram', icon: FaInstagram, color: '#E4405F' },
  { id: 'linkedin', name: 'LinkedIn', icon: FaLinkedin, color: '#0077B5' },
  { id: 'threads', name: 'Threads', icon: FaThreads, color: '#000000' },
  { id: 'tiktok', name: 'TikTok', icon: FaTiktok, color: '#000000' }
];

const PlatformSelector = ({ selectedPlatforms, onPlatformToggle }) => {
  return (
    <div className="platform-selector">
      <h2>🌐 Pilih Platform</h2>
      <p className="platform-hint">Pilih platform tempat Anda ingin memposting konten</p>
      
      <div className="platform-grid">
        {platforms.map(platform => {
          const Icon = platform.icon;
          const isSelected = selectedPlatforms.includes(platform.id);
          
          return (
            <div
              key={platform.id}
              className={`platform-card ${isSelected ? 'selected' : ''}`}
              onClick={() => onPlatformToggle(platform.id)}
            >
              <div className="platform-icon" style={{ color: platform.color }}>
                <Icon />
              </div>
              <div className="platform-name">{platform.name}</div>
              {isSelected && (
                <div className="checkmark">✓</div>
              )}
            </div>
          );
        })}
      </div>
      
      {selectedPlatforms.length > 0 && (
        <div className="selected-info">
          {selectedPlatforms.length} platform dipilih
        </div>
      )}
    </div>
  );
};

export default PlatformSelector;

