import Header from './components/Header';
import NavMenu from './components/NavMenu';
import Footer from './components/Footer';
import RoutesProvider from './routes';

export default function App() {
  return (
    <>
      <Header />
      <NavMenu />
      <RoutesProvider />
      <Footer />
    </>
  );
}

/**
 * APP.JSX - Componente Raiz da Aplicação
 * 
 * Responsabilidades:
 * - Define a estrutura geral da aplicação
 * - Contém componentes que aparecem em TODAS as páginas (Header, Footer, Menu global)
 * - Gerencia o sistema de rotas (RoutesProvider)
 * 
 * Nota: Nesta aplicação, cada página controla sua própria estrutura,
 * portanto App.jsx apenas renderiza as rotas.
 */