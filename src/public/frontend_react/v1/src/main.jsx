import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App";
import "./index.css";

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

/**
 * MAIN.JSX - Ponto de Entrada da Aplicação
 * 
 * Responsabilidades:
 * - Renderiza o componente App no DOM (elemento #root)
 * - Local ideal para Providers globais (Context API, Router wrapping, Theme, etc)
 * - Importa estilos globais (index.css)
 * 
 * Este arquivo NÃO deve conter lógica de negócio.
 */