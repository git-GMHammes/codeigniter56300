/**
 * LINKEDINPROFILECARD - Card de perfil LinkedIn com mesmo formato do ServiceCard
 * 
 * Props:
 * - name: string (nome completo)
 * - title: string (título profissional)
 * - profileImage: string (caminho da imagem)
 * - linkedinUrl: string (URL do LinkedIn)
 * - about: string (sobre você)
 * - services: string (descrição dos serviços)
 * - skills: array (tags de habilidades)
 */

export default function LinkedInProfileCard({
    name = "Gustavo Hammes",
    title = "Desenvolvedor Web fullstack",
    profileImage = null,
    linkedinUrl = "https://www.linkedin.com/in/gustavo-hammes/",
    about = "Tenho habilidades sólidas de comunicação, mantendo sempre o foco nos objetivos. Sou automotivado, especialmente ao identificar e corrigir minhas próprias falhas, o que contribui para meu crescimento profissional.",
    services = "Desenvolvedor Fullstack com experiência em aplicações corporativas, microsserviços e integração de sistemas. Atua em frontend (React, Angular, TypeScript) e backend (PHP, Python, Java básico), além de mobile com Flutter. Domínio em bancos de dados (MySQL, PostgreSQL, SQL Server) e infraestrutura (Docker, Git, APIs REST).",
    skills = [
        "Desenvolvimento web",
        "Gestão da informação",
        "Gestão de conteúdo corporativo",
        "Teste de software",
        "Consultoria de TI",
        "Desenvolvimento de aplicativos móveis",
    ]
}) {
    return (
        <div className="bg-gray-dark rounded-2xl overflow-hidden shadow-card transition-all duration-300 ease-in-out hover:-translate-y-2.5 hover:shadow-card-hover border-2 border-gray-border hover:border-gold">

            {/* Área da imagem - CLICÁVEL - mesmo tamanho do vídeo dos ServiceCards */}
            <a
                href={linkedinUrl}
                target="_blank"
                rel="noopener noreferrer"
                className="block w-full h-[250px] bg-gradient-to-br from-gray-800 via-gray-700 to-gray-900 relative overflow-hidden group"
            >
                {/* Efeito de fundo com padrão */}
                <div className="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                    <div className="absolute top-10 left-10 w-32 h-32 border-4 border-gold rounded-full"></div>
                    <div className="absolute bottom-10 right-10 w-24 h-24 border-4 border-gold rounded-full"></div>
                </div>

                {/* Foto de perfil centralizada - CLICÁVEL */}
                <div className="absolute inset-0 flex items-center justify-center">
                    <div className="w-40 h-40 rounded-full border-4 border-gold bg-gray-700 overflow-hidden shadow-2xl group-hover:border-yellow-500 transition-all group-hover:scale-105">
                        {profileImage ? (
                            <img
                                src={profileImage}
                                alt={name}
                                className="w-full h-full object-cover"
                            />
                        ) : (
                            <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-600 to-gray-800">
                                <svg className="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                        )}
                    </div>
                </div>
            </a>

            {/* Conteúdo do Card - COM SCROLL para manter altura fixa */}
            <div className="p-8 overflow-y-auto max-h-[500px]">

                {/* Título com ícone do LinkedIn - mesmo estilo do ServiceCard */}
                <div className="flex items-center gap-3 mb-5 pb-2.5 border-b-4 border-gold">
                    {/* Ícone do LinkedIn */}
                    <span className="text-gold">
                        <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </span>
                    <h2 className="text-3xl text-gold m-0">
                        {name}
                    </h2>
                </div>

                {/* Título profissional */}
                <p className="text-white text-lg font-semibold mb-4">
                    {title}
                </p>

                {/* Seção Sobre */}
                <div className="mb-5">
                    <h3 className="text-gold text-lg font-bold mb-2 flex items-center gap-2">
                        <span className="text-gold">▸</span>
                        Sobre
                    </h3>
                    <p className="text-gray-medium text-[0.95rem] leading-relaxed">
                        {about}
                    </p>
                </div>

                {/* Seção Serviços */}
                <div className="mb-5">
                    <h3 className="text-gold text-lg font-bold mb-2 flex items-center gap-2">
                        <span className="text-gold">▸</span>
                        Serviços
                    </h3>
                    <p className="text-gray-medium text-[0.95rem] leading-relaxed">
                        {services}
                    </p>
                </div>

                {/* Tags de Habilidades */}
                <div>
                    <h3 className="text-gold text-lg font-bold mb-3 flex items-center gap-2">
                        <span className="text-gold">▸</span>
                        Serviços prestados
                    </h3>
                    <div className="flex flex-wrap gap-2">
                        {skills.map((skill, index) => (
                            <span
                                key={index}
                                className="bg-gray-800 text-gray-300 text-xs px-3 py-1.5 rounded-full border border-gray-600 hover:border-gold hover:text-white transition-colors"
                            >
                                {skill}
                            </span>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}