const fs = require('fs-extra');

async function copyAssets() {
    try {
        await fs.copy('resources/assets/js', 'public/js');
        await fs.copy('resources/assets/css', 'public/css');
        await fs.copy('resources/assets/images', 'public/images');
        await fs.copy('resources/assets/fonts', 'public/fonts');
        console.log('✅ Assets copied to public directory.');
    } catch (err) {
        console.error('❌ Error copying assets:', err);
    }
}

copyAssets();
