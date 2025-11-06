# Download GSAP Library Files

To use this plugin, you need to download the GSAP library files from GreenSock.

## Option 1: Download from CDN (Recommended for Development)

Visit the GSAP CDN links below and save the files to this directory:

1. **gsap.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js
   - Save as: `gsap.min.js`

2. **ScrollTrigger.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js
   - Save as: `ScrollTrigger.min.js`

3. **ScrollToPlugin.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollToPlugin.min.js
   - Save as: `ScrollToPlugin.min.js`

4. **Draggable.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/Draggable.min.js
   - Save as: `Draggable.min.js`

5. **MotionPathPlugin.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/MotionPathPlugin.min.js
   - Save as: `MotionPathPlugin.min.js`

6. **EasePack.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/EasePack.min.js
   - Save as: `EasePack.min.js`

7. **TextPlugin.min.js**
   - URL: https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/TextPlugin.min.js
   - Save as: `TextPlugin.min.js`

## Option 2: Download from Official Website

1. Visit https://greensock.com/gsap/
2. Download the GSAP package
3. Extract the files from the `dist` folder
4. Copy the required files to this directory

## Option 3: Use NPM (if you have Node.js)

```bash
npm install gsap
```

Then copy the files from `node_modules/gsap/dist/` to this directory.

## Option 4: Quick PowerShell Script

Run this PowerShell script in this directory to download all files:

```powershell
$files = @(
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"; name="gsap.min.js"},
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"; name="ScrollTrigger.min.js"},
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollToPlugin.min.js"; name="ScrollToPlugin.min.js"},
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/Draggable.min.js"; name="Draggable.min.js"},
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/MotionPathPlugin.min.js"; name="MotionPathPlugin.min.js"},
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/EasePack.min.js"; name="EasePack.min.js"},
    @{url="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/TextPlugin.min.js"; name="TextPlugin.min.js"}
)

foreach ($file in $files) {
    Write-Host "Downloading $($file.name)..."
    Invoke-WebRequest -Uri $file.url -OutFile $file.name
    Write-Host "Downloaded $($file.name)" -ForegroundColor Green
}

Write-Host "`nAll files downloaded successfully!" -ForegroundColor Green
```

## Verification

After downloading, you should have these files in this directory:
- ✅ gsap.min.js
- ✅ ScrollTrigger.min.js
- ✅ ScrollToPlugin.min.js
- ✅ Draggable.min.js
- ✅ MotionPathPlugin.min.js
- ✅ EasePack.min.js
- ✅ TextPlugin.min.js
- ✅ mascot-gsap-helper.js (already included)

## License Note

GSAP is licensed under GreenSock's standard license. For commercial use, you may need a commercial license from GreenSock.
Visit https://greensock.com/licensing/ for more information.

