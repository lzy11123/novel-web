import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
// 引入windicss
import WindiCSS from 'vite-plugin-windicss'

import path from "path"

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    //配置src别名
    alias: {
      '~': path.resolve(__dirname, 'src')
    }
  },

  // 跨域代理
  server: {
    proxy: {
      '/api': {
        target: 'http://www.lin123.com',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '')
      },
    }
  },
  plugins: [vue(), WindiCSS()]
})
