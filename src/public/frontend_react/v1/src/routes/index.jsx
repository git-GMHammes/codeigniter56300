import { HashRouter, Routes, Route } from "react-router-dom";
import HomePage from "../pages/home_page";
import AboutPage from "../pages/about_page";

export default function RoutesProvider() {
  return (
    <HashRouter>
      <Routes>
        {/* Rota para Home Page */}
        <Route path="/" element={<HomePage />} />
        {/* Rota para About Page */}
        <Route path="/about" element={<AboutPage />} />
      </Routes>
    </HashRouter>
  );
}