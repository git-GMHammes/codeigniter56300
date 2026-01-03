import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig(({ command, mode }) => {
  // Lógica automática do base path
  let basePath = '/'; // Default para npm run dev

  if (command === 'build') {
    // Se está buildando, define o base conforme o ambiente
    basePath = mode === 'production'
      ? '/codeigniter56300/src/public/react-app/' // PRD
      : '/react-app/'; // DEV
  }

  console.info('🚀 Command:', command);
  console.info('🌍 Mode:', mode);
  console.info('📍 Base Path:', basePath);

  return {
    plugins: [react()],
    base: basePath,
    resolve: {
      alias: {
        "@": path.resolve(__dirname, "./src"),
      },
    },
    server: {
      host: '127.0.0.1',
      port: 7777,
    },
    build: {
      outDir: './dist',
      assetsDir: 'assets',
      rollupOptions: {
        output: {
          manualChunks: undefined,
          entryFileNames: 'assets/[name]-[hash].js',
          chunkFileNames: 'assets/[name]-[hash].js',
          assetFileNames: (assetInfo) => {
            const info = assetInfo.name.split('.');
            const ext = info[info.length - 1];

            if (/\.(mp4|webm|ogg|mp3|wav|flac|aac)$/i.test(assetInfo.name)) {
              return `assets/videos/[name]-[hash][extname]`;
            }
            if (/\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
              return `assets/imagens/[name]-[hash][extname]`;
            }
            if (ext === 'css') {
              return `assets/[name]-[hash][extname]`;
            }
            return `assets/[name]-[hash][extname]`;
          }
        }
      },
      modulePreload: {
        polyfill: true
      },
      minify: 'esbuild',
    },
  };
});
