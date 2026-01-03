// src/utils/assets.js

/**
 * Retorna o caminho correto do asset baseado no ambiente
 * @param {string} assetPath - Caminho do asset (ex: 'assets/videos/video.mp4')
 * @returns {string} - URL completa do asset
 */
export const getAssetUrl = (assetPath) => {
  const baseUrl = import.meta.env.BASE_URL;
  // Remove a barra inicial se existir
  const cleanPath = assetPath.startsWith('/') ? assetPath.slice(1) : assetPath;
  return `${baseUrl}${cleanPath}`;
};

/**
 * Helper específico para vídeos
 */
export const getVideoUrl = (videoName) => {
  return getAssetUrl(`assets/videos/${videoName}`);
};

/**
 * Helper específico para imagens
 */
export const getImageUrl = (imageName) => {
  return getAssetUrl(`assets/imagens/${imageName}`);
};