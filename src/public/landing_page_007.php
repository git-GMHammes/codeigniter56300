<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços de Desenvolvimento de Sistemas - Habilidade.Com</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #000000;
            color: #ffffff;
            line-height: 1.6;
        }

        /* Header com vídeo de fundo */
        header {
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.3);
            position: relative;
            overflow: hidden;
            min-height: 600px;
        }

        /* VÍDEO DE FUNDO */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        /* OVERLAY (gradiente amarelo sobre o vídeo) */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.7) 0%, rgba(255, 165, 0, 0.7) 100%);
            z-index: 1;
            pointer-events: none;
        }

        /* CONTEÚDO ACIMA DO VÍDEO */
        .header-content {
            position: relative;
            z-index: 2;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        /* CÍRCULO */
        .logo-wolf {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid #000;
            background: #FFD700;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        /* IMAGEM */
        .logo-wolf img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .company-name {
            font-size: 4rem;
            font-weight: 900;
            color: #000000;
            letter-spacing: 3px;
            text-shadow: 3px 3px 0px rgba(0, 0, 0, 0.1);
            margin: 0;
        }

        .company-name .dot-com {
            color: #1a1a1a;
            font-weight: 700;
            font-style: italic;
            ✨ NOVO ! transform: skewX(-18deg);
            ✨ NOVO ! Inclinação forte display: inline-block;
            ✨ NOVO !
        }

        /* TÍTULO COM EFEITOS MODERNOS E IMPACTANTES */
        header h1 {
            display: inline-block;
            font-size: 1.5rem;
            margin: 30px 0 20px 0;
            font-weight: 900;
            letter-spacing: 8px;
            text-transform: uppercase;
            padding: 25px 45px;
            position: relative;
            color: #000000;
            background: linear-gradient(45deg, #000000, #1a1a1a, #000000, #1a1a1a);
            background-size: 300% 300%;
            border-radius: 15px;
            animation: gradientShift 4s ease infinite;
            box-shadow:
                0 0 30px rgba(255, 215, 0, 0.5),
                0 0 60px rgba(255, 165, 0, 0.3),
                inset 0 0 20px rgba(255, 215, 0, 0.1);
        }

        /* Animação de gradiente de fundo em movimento */
        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        /* Texto com gradiente animado */
        header h1 span {
            background: linear-gradient(45deg, #FFD700, #FFA500, #FFD700, #FFA500);
            background-size: 300% 300%;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textGlow 3s ease infinite;
        }

        @keyframes textGlow {

            0%,
            100% {
                background-position: 0% 50%;
                filter: brightness(1);
            }

            50% {
                background-position: 100% 50%;
                filter: brightness(1.3);
            }
        }

        /* Brilho pulsante ao redor do título */
        header h1::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg, #FFD700, #FFA500, #FFD700, #FFA500);
            background-size: 400% 400%;
            border-radius: 15px;
            z-index: -1;
            animation: borderGlow 3s ease infinite;
        }

        @keyframes borderGlow {

            0%,
            100% {
                opacity: 0.8;
                background-position: 0% 50%;
            }

            50% {
                opacity: 1;
                background-position: 100% 50%;
            }
        }

        /* Linha decorativa inferior animada */
        header h1::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg,
                    transparent,
                    #FFD700 20%,
                    #FFA500 50%,
                    #FFD700 80%,
                    transparent);
            border-radius: 2px;
            animation: lineGlow 2s ease-in-out infinite;
        }

        @keyframes lineGlow {

            0%,
            100% {
                opacity: 0.5;
                box-shadow: 0 0 10px #FFD700;
            }

            50% {
                opacity: 1;
                box-shadow: 0 0 20px #FFD700, 0 0 30px #FFA500;
            }
        }

        header p {
            font-size: 1.4rem;
            color: #1a1a1a;
            font-weight: 500;
            margin-top: 30px;
            font-style: italic;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        /* Grid de Cards */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        /* Card */
        .service-card {
            background: #1a1a1a;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid #333;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255, 215, 0, 0.3);
            border-color: #FFD700;
        }

        .card-video {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #000;
        }

        /* Conteúdo do Card */
        .card-content {
            padding: 30px;
        }

        .card-content h2 {
            font-size: 1.8rem;
            color: #FFD700;
            margin-bottom: 20px;
            border-bottom: 3px solid #FFD700;
            padding-bottom: 10px;
        }

        .card-content ul {
            list-style: none;
            padding: 0;
        }

        .card-content li {
            padding: 10px 0;
            padding-left: 25px;
            position: relative;
            color: #cccccc;
            font-size: 0.95rem;
        }

        .card-content li:before {
            content: "▸";
            position: absolute;
            left: 0;
            color: #FFD700;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            padding: 40px 20px;
            text-align: center;
            margin-top: 60px;
        }

        footer p {
            color: #000;
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* CTA Button */
        .cta-button {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 40px;
            background-color: #000;
            color: #FFD700;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid #FFD700;
        }

        .cta-button:hover {
            background-color: #FFD700;
            color: #000;
            transform: scale(1.05);
        }

        /* Responsivo */
        @media (max-width: 768px) {
            header {
                min-height: 500px;
            }

            .logo-container {
                flex-direction: column;
                gap: 20px;
            }

            .logo-wolf {
                width: 100px;
                height: 100px;
            }

            .company-name {
                font-size: 2.5rem;
            }

            header h1 {
                font-size: 1rem;
                letter-spacing: 2px;
                padding: 10px 15px;
                max-width: 95%;
            }

            header p {
                font-size: 0.85rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .card-content h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header com vídeo de fundo -->
    <header>
        <!-- VÍDEO DE FUNDO -->
        <video autoplay loop muted playsinline class="video-background">
            <source src="./assets/videos/fundo_video005.mp4" type="video/mp4">
            Seu navegador não suporta vídeos HTML5.
        </video>

        <!-- OVERLAY (gradiente amarelo) -->
        <div class="overlay"></div>

        <!-- CONTEÚDO DO HEADER -->
        <div class="header-content">
            <div class="logo-container">
                <div class="logo-wolf">
                    <img src="./assets/imagens/Eu.png" alt="Logo Habilidade.com">
                </div>

                <h2 class="company-name">
                    HABILIDADE<span class="dot-com"> .Com</span>
                </h2>
            </div>
            <h1><span>Serviços de Desenvolvimento de Sistemas</span></h1>
            <p>Soluções completas em tecnologia para o seu negócio</p>
        </div>
    </header>

    <!-- Container Principal -->
    <div class="container">
        <div class="services-grid">

            <!-- Card 1: Desenvolvimento de Software -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video001.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Desenvolvimento de Software</h2>
                    <ul>
                        <li>Desenvolvimento de sistemas web (portais, e-commerce, dashboards)</li>
                        <li>Desenvolvimento de aplicativos móveis (Android/iOS)</li>
                        <li>Desenvolvimento de software desktop</li>
                        <li>Sistemas SaaS (Software as a Service)</li>
                        <li>Progressive Web Apps (PWA)</li>
                    </ul>
                </div>
            </div>

            <!-- Card 2: Análise e Consultoria -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video002.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Análise e Consultoria</h2>
                    <ul>
                        <li>Levantamento de requisitos</li>
                        <li>Análise de viabilidade técnica</li>
                        <li>Modelagem de processos de negócio</li>
                        <li>Auditoria de sistemas existentes</li>
                        <li>Consultoria em arquitetura de software</li>
                    </ul>
                </div>
            </div>

            <!-- Card 3: Integração e APIs -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video003.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Integração e APIs</h2>
                    <ul>
                        <li>Desenvolvimento de APIs REST/GraphQL</li>
                        <li>Integração entre sistemas legados e modernos</li>
                        <li>Integração com serviços de terceiros (pagamento, ERP, CRM)</li>
                        <li>Desenvolvimento de microserviços</li>
                        <li>Web Services (SOAP/REST)</li>
                    </ul>
                </div>
            </div>

            <!-- Card 4: Banco de Dados -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video004.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Banco de Dados</h2>
                    <ul>
                        <li>Modelagem e design de banco de dados</li>
                        <li>Otimização e performance</li>
                        <li>Migração de dados</li>
                        <li>Administração e manutenção</li>
                        <li>Business Intelligence e Data Warehouse</li>
                    </ul>
                </div>
            </div>

            <!-- Card 5: Manutenção e Suporte -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video005.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Manutenção e Suporte</h2>
                    <ul>
                        <li>Manutenção corretiva, evolutiva e adaptativa</li>
                        <li>Suporte técnico especializado</li>
                        <li>Monitoramento de sistemas</li>
                        <li>Gestão de incidentes</li>
                        <li>Documentação técnica</li>
                    </ul>
                </div>
            </div>

            <!-- Card 6: DevOps e Infraestrutura -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video006.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>DevOps e Infraestrutura</h2>
                    <ul>
                        <li>Configuração de servidores e cloud</li>
                        <li>Implementação de CI/CD</li>
                        <li>Containerização (Docker/Kubernetes)</li>
                        <li>Automação de deploys</li>
                        <li>Backup e disaster recovery</li>
                    </ul>
                </div>
            </div>

            <!-- Card 7: Segurança -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video007.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Segurança</h2>
                    <ul>
                        <li>Auditoria de segurança</li>
                        <li>Implementação de autenticação e autorização</li>
                        <li>Proteção contra vulnerabilidades</li>
                        <li>Conformidade com LGPD/GDPR</li>
                        <li>Testes de penetração</li>
                    </ul>
                </div>
            </div>

            <!-- Card 8: Outros Serviços -->
            <div class="service-card">
                <video class="card-video" autoplay loop muted playsinline>
                    <source src="./assets/videos/tab_video008.mp4" type="video/mp4">
                    Seu navegador não suporta vídeos HTML5.
                </video>
                <div class="card-content">
                    <h2>Outros Serviços</h2>
                    <ul>
                        <li>Prototipagem e MVP</li>
                        <li>Migração de sistemas legados</li>
                        <li>Automatização de processos (RPA)</li>
                        <li>Testes de software (unitários, integração, performance)</li>
                        <li>Treinamento de usuários</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Transforme seu negócio com tecnologia de ponta</p>
        <a href="https://wa.me/5521980558545?text=Olá!%20Vim%20pelo%20site%20e%20gostaria%20de%20conhecer%20mais%20sobre%20os%20serviços%20de%20desenvolvimento%20de%20sistemas."
            target="_blank" class="cta-button">
            Entre em Contato
        </a>
    </footer>

</body>

</html>