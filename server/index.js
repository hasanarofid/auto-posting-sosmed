const express = require('express');
const cors = require('cors');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Setup upload directory
const uploadDir = path.join(__dirname, 'uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

// Multer configuration untuk upload gambar
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, uploadDir);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, uniqueSuffix + path.extname(file.originalname));
  }
});

const upload = multer({
  storage: storage,
  limits: { fileSize: 10 * 1024 * 1024 }, // 10MB
  fileFilter: (req, file, cb) => {
    const allowedTypes = /jpeg|jpg|png|gif|mp4|mov/;
    const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
    const mimetype = allowedTypes.test(file.mimetype);
    
    if (mimetype && extname) {
      return cb(null, true);
    } else {
      cb(new Error('Hanya file gambar (jpeg, jpg, png, gif) atau video (mp4, mov) yang diizinkan!'));
    }
  }
});

// Import routes
const facebookRoutes = require('./routes/facebook');
const instagramRoutes = require('./routes/instagram');
const linkedinRoutes = require('./routes/linkedin');
const threadsRoutes = require('./routes/threads');
const tiktokRoutes = require('./routes/tiktok');
const contentRoutes = require('./routes/content');

// Routes
app.use('/api/facebook', facebookRoutes);
app.use('/api/instagram', instagramRoutes);
app.use('/api/linkedin', linkedinRoutes);
app.use('/api/threads', threadsRoutes);
app.use('/api/tiktok', tiktokRoutes);
app.use('/api/content', contentRoutes);

// Upload endpoint
app.post('/api/upload', upload.single('image'), (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ error: 'Tidak ada file yang diupload' });
    }
    
    res.json({
      success: true,
      file: {
        filename: req.file.filename,
        originalname: req.file.originalname,
        path: `/uploads/${req.file.filename}`,
        size: req.file.size
      }
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Serve uploaded files
app.use('/uploads', express.static(uploadDir));

// Health check
app.get('/api/health', (req, res) => {
  res.json({ status: 'OK', message: 'Server berjalan dengan baik' });
});

app.listen(PORT, () => {
  console.log(`Server berjalan di port ${PORT}`);
});

