const express = require('express');
const router = express.Router();
const fs = require('fs');
const path = require('path');

// Simpan konten ke file JSON (bisa diganti dengan database)
const contentFile = path.join(__dirname, '../data/content.json');

// Helper function untuk read/write content
const readContent = () => {
  if (!fs.existsSync(contentFile)) {
    return [];
  }
  return JSON.parse(fs.readFileSync(contentFile, 'utf8'));
};

const writeContent = (content) => {
  const dir = path.dirname(contentFile);
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
  fs.writeFileSync(contentFile, JSON.stringify(content, null, 2));
};

// Get all content
router.get('/', (req, res) => {
  try {
    const content = readContent();
    res.json(content);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Create new content
router.post('/', (req, res) => {
  try {
    const { caption, image, platforms, schedule } = req.body;
    
    if (!caption || !image) {
      return res.status(400).json({ error: 'Caption dan gambar diperlukan' });
    }
    
    const content = readContent();
    const newContent = {
      id: Date.now().toString(),
      caption,
      image,
      platforms: platforms || [],
      schedule: schedule || null,
      status: 'draft',
      createdAt: new Date().toISOString()
    };
    
    content.push(newContent);
    writeContent(content);
    
    res.json({ success: true, content: newContent });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Update content
router.put('/:id', (req, res) => {
  try {
    const { id } = req.params;
    const updates = req.body;
    
    const content = readContent();
    const index = content.findIndex(c => c.id === id);
    
    if (index === -1) {
      return res.status(404).json({ error: 'Konten tidak ditemukan' });
    }
    
    content[index] = { ...content[index], ...updates };
    writeContent(content);
    
    res.json({ success: true, content: content[index] });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Delete content
router.delete('/:id', (req, res) => {
  try {
    const { id } = req.params;
    
    const content = readContent();
    const filtered = content.filter(c => c.id !== id);
    
    if (content.length === filtered.length) {
      return res.status(404).json({ error: 'Konten tidak ditemukan' });
    }
    
    writeContent(filtered);
    res.json({ success: true });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

module.exports = router;

