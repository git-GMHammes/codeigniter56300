import { useState, useEffect } from "react";
import "./styles.css";
// import logoserviceNote from "../../assets/images/logoserviceNote.png";

// Componente HcLoading com efeito de blur e bloqueio de interação
const HcLoading = ({ openLoading = false }) => {
    // Lista de efeitos disponíveis
    // const efeitos = ["zoomIn", "zoomOut", "swing", "rotate", "shake", "pulsate", "elastic"];

    // Estado para armazenar o efeito selecionado
    // const [efeitoAtual, setEfeitoAtual] = useState("");
    const [loadingTrue, setLoadingTrue] = useState(openLoading);

    // Atualiza o estado quando a prop mudar
    useEffect(() => {
        setLoadingTrue(openLoading);
    }, [openLoading]);

    // useEffect(() => {
    //     // Pula o restante do efeito se não estiver carregando
    //     if (!loadingTrue) return;

    //     // Função para selecionar um efeito aleatório
    //     const selecionarEfeitoAleatorio = () => {
    //         const indiceAleatorio = Math.floor(Math.random() * efeitos.length);
    //         return efeitos[indiceAleatorio];
    //     };

    //     // Seleciona um efeito aleatório quando o componente é montado
    //     setEfeitoAtual(selecionarEfeitoAleatorio());

    //     // Define um intervalo para trocar o efeito a cada 2 segundos
    //     const intervalo = setInterval(() => {
    //         setEfeitoAtual(selecionarEfeitoAleatorio());
    //     }, 2000);

    //     // Bloqueia a rolagem do body quando o loading estiver ativo
    //     document.body.style.overflow = "hidden";

    //     // Limpa o intervalo e restaura o overflow quando o componente é desmontado
    //     return () => {
    //         clearInterval(intervalo);
    //         document.body.style.overflow = "auto";
    //     };
    // }, [loadingTrue]);
  

    // Renderização condicional deve ser feita aqui, fora do useEffect
    if (!loadingTrue) return null;

    return (
        <div className="loading-overlay">
            <div className="loading-blur-background"></div>
            <div className="loading-content">
                <img
                    src={logoserviceNote}
                    alt="Logo serviceNote"
                    className={`img-fluid`}
                    style={{ maxHeight: "80px" }}
                />
                <div className="loading-bar">
                    <div className="loading-bar-fill"></div>
                </div>
                <p className="loading-text">Carregando...</p>
            </div>
        </div>
    );
};

export default HcLoading;