# 🌟 Backup – Configuração do Projeto React com Vite e Tailwind CSS

---

## ⚙️ **Regras de Ouro**

Para o futuro desenvolvimento do projeto, estas regras devem ser seguidas estritamente:

### **1. Arquitetura de Pastas e Arquivos**

- Seguir sempre a estrutura definida com **pastas e arquivos organizados**, utilizando padrões como:
  - **`index.jsx`** dentro das pastas.
  - Seguir pastas já existentes como `routes`, `pages`, etc.

### **2. Estilização: 1% CSS e 99% Tailwind**

- Priorizar **Tailwind CSS** para styling.
- Utilizar CSS apenas em casos onde não seja possível resolver com Tailwind, mantendo o percentual de **1% CSS e 99% Tailwind**.

---

## 📂 Estrutura do Projeto

A arquitetura atual do projeto baseada no arquivo original **est.txt** está organizada assim:

### **Estrutura de Pastas**

```plaintext
frontend_react\v1
├── public
│   ├── vite.svg (.svg)
├── src
│   ├── assets
│   │   └── react.svg (.svg)
│   ├── components
│   │   ├── Auth
│   │   ├── HcButton
│   │   ├── HcFooter
│   │   ├── ...
│   │   └── index.js (.js)
│   ├── pages
│   │   ├── home_page
│   │   │   └── index.jsx (.jsx)
│   │   ├── about_page
│   │   │   └── index.jsx (.jsx)
│   ├── routes
│   │   └── index.jsx (.jsx)
│   ├── styles
│   ├── utils
│   ├── App.css (.css)
│   ├── App.jsx (.jsx)
│   ├── index.css (.css)
│   ├── main.jsx (.jsx)
│   └── tailwind.config.js (.js)
├── index.html (.html)
└── vite.config.js (.js)
```

---

## 🛠️ **Funcionalidades Implementadas**

### 1. **Instalação e Configuração**

- **Ferramentas Instaladas:**
  - `vite`, `react`, `react-dom`
  - `tailwindcss`, `postcss`, `autoprefixer`
  - `react-router-dom`
- **Tailwind Configurado:**

  - Arquivo: `tailwind.config.js`

  ```javascript
  /** @type {import('tailwindcss').Config} */
  export default {
    content: ["./index.html", "./src/**/*.{jsx,js,ts,tsx}"],
    theme: {
      extend: {},
    },
    plugins: [],
  };
  ```

- **Arquivos Base Estilizados com Tailwind:**
  - `index.css`:
    ```css
    @tailwind base;
    @tailwind components;
    @tailwind utilities;
    ```

---

### 2. **Estrutura Profissional**

#### **Páginas:**

- **`home_page/index.jsx`:**

  ```javascript
  export default function HomePage() {
    return (
      <div className="flex flex-col items-center justify-center h-screen bg-gray-100">
        <h1 className="text-5xl font-bold text-blue-500">
          Bem-vindo à Home Page
        </h1>
        <p className="mt-4 text-xl text-gray-800 text-center">
          Esta é uma página inicial estilizada com Tailwind CSS.
        </p>
      </div>
    );
  }
  ```

- **`about_page/index.jsx`:**
  ```javascript
  export default function AboutPage() {
    return (
      <div className="flex flex-col items-center justify-center h-screen bg-gray-50">
        <h1 className="text-4xl font-bold text-green-500">Sobre o Projeto</h1>
        <p className="mt-4 text-lg text-gray-800 text-center">
          Este é um aplicativo React configurado com Tailwind CSS e rotas.
          Criado para oferecer a melhor estrutura profissional!
        </p>
      </div>
    );
  }
  ```

---

#### **Rotas:**

- **`routes/index.jsx`:**

  ```javascript
  import { HashRouter, Routes, Route } from "react-router-dom";
  import HomePage from "../pages/home_page";
  import AboutPage from "../pages/about_page";

  export default function RoutesProvider() {
    return (
      <HashRouter>
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/about" element={<AboutPage />} />
        </Routes>
      </HashRouter>
    );
  }
  ```

- **`App.jsx` (Carregando Rotas):**

  ```javascript
  import RoutesProvider from "./routes";

  function App() {
    return <RoutesProvider />;
  }

  export default App;
  ```

---

### **URLs Criadas**

1. **Página Inicial (Home Page):**

   - URL: [http://127.0.0.1:7777/#/](http://127.0.0.1:7777/#/)

2. **Página Sobre (About Page):**
   - URL: [http://127.0.0.1:7777/#/about](http://127.0.0.1:7777/#/about)

---

### ✅ **Próximos Passos**

1. Expandir a estrutura com mais páginas e funcionalidades.
2. Garantir que todo estilo siga prioridade para **Tailwind CSS** (99%).
3. Adicionar novos componentes reutilizáveis (por exemplo, Header e Footer com navegação).

---

🎉 **Gratidão pela confiança! Qualquer ajuste ou expansão do projeto, conte comigo! 😊**
