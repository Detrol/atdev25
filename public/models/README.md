# 3D Models for Product Viewer Demo

This directory contains GLB 3D models for the Product Viewer showcase demo.

## Required Models

Download the following GLB models and place them in this directory:

### 1. Armchair (Fåtölj)
**Filename:** `armchair.glb`
**Source:** [Poly Pizza - Modern Armchair](https://poly.pizza)
**Alternative:** Search Sketchfab for "modern armchair" (filter: Downloadable, GLB format)
**Size:** < 5MB
**Use Case:** Perfect for furniture stores, interior design

### 2. Table Lamp (Bordslampa)
**Filename:** `lamp.glb`
**Source:** [Poly Pizza - Table Lamp](https://poly.pizza)
**Alternative:** Search Sketchfab for "table lamp"
**Size:** < 3MB
**Use Case:** Home decor, lighting stores

### 3. Vase (Vas)
**Filename:** `vase.glb`
**Source:** [Poly Pizza - Modern Vase](https://poly.pizza)
**Alternative:** Search Sketchfab for "decorative vase"
**Size:** < 2MB
**Use Case:** Home goods, decor shops

### 4. Sculpture (Skulptur) - Optional
**Filename:** `sculpture.glb`
**Source:** [Poly Pizza - Abstract Sculpture](https://poly.pizza)
**Alternative:** Search Sketchfab for "abstract sculpture"
**Size:** < 4MB
**Use Case:** Art galleries, design studios

## Where to Find Free 3D Models

### 1. Poly Pizza (Recommended)
**URL:** https://poly.pizza
**License:** CC0 (Public Domain)
**Format:** GLB available
**Quality:** Good for web demos

### 2. Sketchfab
**URL:** https://sketchfab.com
**License:** Check individual models (look for CC licenses)
**Format:** Filter by "Downloadable" and select GLB
**Quality:** Professional, high detail

### 3. Google Poly Archive
**URL:** Archive available via search
**License:** Mostly CC licenses
**Format:** GLB/GLTF
**Quality:** Varied

## Model Requirements

- **Format:** GLB (binary GLTF)
- **Size:** Preferably < 5MB per model
- **Compression:** Use Draco compression if possible
- **Textures:** Embedded in GLB file
- **Optimization:** Remove unnecessary geometry, optimize textures

## How to Download from Poly Pizza

1. Visit https://poly.pizza
2. Search for the model type (e.g., "armchair")
3. Click on a model you like
4. Click "Download" button
5. Select "GLB" format
6. Save to this directory with the correct filename

## How to Download from Sketchfab

1. Visit https://sketchfab.com
2. Search for the model (e.g., "modern lamp")
3. Filter: "Downloadable" + "Free" (optional)
4. Click on a model
5. Check license (prefer CC0, CC-BY)
6. Click "Download 3D Model"
7. Select "glTF Binary (.glb)"
8. Save to this directory

## Poster Images

For each GLB model, you should also create a poster image (preview):

- **Location:** `public/images/products/`
- **Format:** JPG or PNG
- **Size:** 800x800px (square)
- **Filename:** Match the GLB name (e.g., `armchair.jpg`)

**How to create:**
- Screenshot from Sketchfab/Poly Pizza
- Or render in Blender
- Or use Model-Viewer's poster generation feature

## Testing Models

Test your GLB models before using:

1. **Online Viewer:** https://gltf-viewer.donmccurdy.com
2. **Model-Viewer:** https://modelviewer.dev/editor
3. **Check:**
   - Model loads quickly
   - Textures display correctly
   - File size is reasonable
   - AR mode works on mobile

## Optimization Tools

If models are too large:

- **gltf-pipeline:** https://github.com/CesiumGS/gltf-pipeline
- **Draco compression:** Reduces size by 50-70%
- **Texture optimization:** Resize textures to 1024x1024 or lower

## License Notes

Always check the license before using 3D models:
- **CC0:** Public domain, no attribution needed
- **CC-BY:** Attribution required
- **CC-BY-SA:** Attribution + share-alike
- **Commercial licenses:** May require payment

For the ATDev demo, prefer CC0 or CC-BY models.

---

**Status:** Awaiting model files
**Last updated:** 2025-01-11
