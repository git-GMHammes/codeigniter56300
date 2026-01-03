import ServiceCard from '../../components/ServiceCard';
import LinkedInProfileCard from '@/components/LinkedInProfileCard';

export default function HomePage() {
  const services = [
    {
      id: "desenvolvimento-software",
      title: "Desenvolvimento de Software",
      videoSrc: "assets/videos/tab_video001.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
        </svg>
      ),
      items: [
        {
          name: "Desenvolvimento de sistemas web (portais, e-commerce, dashboards)",
          description: [
            "O desenvolvimento de sistemas web, como portais, e-commerce e dashboards, será implementado utilizando uma estrutura robusta e moderna, que combina um backend poderoso com uma interface de usuário dinâmica. Para o backend, será adotado o PHP, uma linguagem amplamente utilizada para o desenvolvimento de soluções web devido à sua flexibilidade, segurança e eficiência no gerenciamento de dados e lógica de negócios. A lógica e os serviços do sistema serão cuidadosamente projetados para garantir o melhor desempenho e escalabilidade, permitindo o suporte a um grande volume de dados e acessos simultâneos.",
            "Na camada de frontend, será empregado o React juntamente com JavaScript, tecnologias que proporcionam uma experiência de usuário rica e interativa. Através dessa abordagem, serão criadas telas intuitivas e responsivas, com componentes reutilizáveis e bem estruturados, permitindo que os usuários naveguem rapidamente entre as seções e tenham acesso às informações de maneira clara e organizada. Além disso, o uso do React facilita a integração dinâmica com o backend, tornando a atualização de dados em tempo real uma característica-chave do sistema.",
            "Complementando esse desenvolvimento, serão implementadas extensões e ferramentas específicas para a exibição precisa dos dados, seja em gráficos, tabelas ou outros formatos personalizados. Essas extensões terão como objetivo destacar os indicadores mais importantes, melhorar a visualização das informações e proporcionar flexibilidade na análise e manipulação de dados. Combinando essas tecnologias, entregaremos soluções completas, modernas e eficientes, alinhadas às necessidades específicas do projeto."
          ]
        },
        {
          name: "Desenvolvimento de aplicativos móveis (Android/iOS)",
          description: [
            "O desenvolvimento de aplicativos móveis para Android e iOS será realizado utilizando tecnologias nativas e híbridas, garantindo a melhor performance e experiência do usuário em ambas as plataformas. Utilizaremos frameworks modernos como React Native ou Flutter, que permitem o desenvolvimento multiplataforma com código compartilhado, reduzindo tempo e custos de desenvolvimento.",
            "Os aplicativos serão projetados seguindo as diretrizes de design de cada plataforma (Material Design para Android e Human Interface Guidelines para iOS), garantindo uma interface familiar e intuitiva para os usuários. A arquitetura do aplicativo será construída com foco em escalabilidade, segurança e performance, incluindo cache offline, sincronização de dados e otimização de consumo de bateria.",
            "Além disso, implementaremos funcionalidades avançadas como notificações push, geolocalização, integração com APIs nativas, pagamentos in-app e muito mais. Todo o processo incluirá testes rigorosos em diferentes dispositivos e versões de sistema operacional, garantindo qualidade e estabilidade do produto final."
          ]
        },
        {
          name: "Desenvolvimento de software desktop",
          description: [
            "O desenvolvimento de software desktop envolve a criação de aplicações robustas e eficientes que rodam diretamente no sistema operacional do usuário. Utilizaremos tecnologias como Electron, que permite desenvolver aplicações desktop multiplataforma usando tecnologias web (HTML, CSS, JavaScript), ou frameworks nativos específicos para cada sistema operacional.",
            "As aplicações desktop desenvolvidas terão acesso completo aos recursos do sistema, incluindo gerenciamento de arquivos, integração com hardware, processamento offline e melhor performance para tarefas pesadas. Isso é especialmente importante para sistemas que exigem processamento intensivo, manipulação de grandes volumes de dados ou operações que requerem acesso direto aos recursos do computador.",
            "Garantiremos que o software seja otimizado para diferentes sistemas operacionais (Windows, macOS, Linux), com instaladores apropriados, atualizações automáticas e uma interface de usuário nativa que se integra perfeitamente ao ambiente do usuário. A segurança dos dados e a estabilidade do sistema serão prioridades em todo o desenvolvimento."
          ]
        },
        {
          name: "Sistemas SaaS (Software as a Service)",
          description: [
            "Sistemas SaaS (Software as a Service) são soluções baseadas em nuvem que permitem aos usuários acessar aplicações através da internet, eliminando a necessidade de instalação local. Desenvolveremos plataformas SaaS escaláveis, seguras e multi-tenant, onde múltiplos clientes compartilham a mesma infraestrutura enquanto mantêm seus dados completamente isolados e protegidos.",
            "A arquitetura será construída utilizando microserviços e containers, permitindo escalabilidade horizontal e vertical conforme a demanda cresce. Implementaremos sistemas de autenticação robustos, gerenciamento de assinaturas, billing automatizado, APIs para integrações e dashboards administrativos completos para gestão de clientes e recursos.",
            "Garantiremos alta disponibilidade através de infraestrutura redundante, backups automáticos, monitoramento 24/7 e planos de disaster recovery. Os sistemas SaaS desenvolvidos seguirão as melhores práticas de segurança, incluindo criptografia de dados, conformidade com LGPD/GDPR e auditorias regulares de segurança."
          ]
        },
        {
          name: "Progressive Web Apps (PWA)",
          description: [
            "Progressive Web Apps (PWA) são aplicações web modernas que combinam o melhor das tecnologias web e móveis, oferecendo experiências ricas e envolventes. As PWAs desenvolvidas funcionarão offline, terão instalação simplificada, notificações push e performance comparável a aplicativos nativos, tudo isso através de um navegador web.",
            "Utilizaremos Service Workers para cache inteligente de recursos, permitindo que a aplicação funcione mesmo sem conexão à internet. A interface será responsiva e adaptável a qualquer tamanho de tela, desde smartphones até desktops, garantindo uma experiência consistente e otimizada em todos os dispositivos.",
            "As PWAs serão otimizadas para carregamento rápido, seguindo as métricas Core Web Vitals do Google, e serão completamente indexáveis por motores de busca, melhorando o SEO. Implementaremos recursos avançados como geolocalização, acesso à câmera, compartilhamento de arquivos e integração com o sistema operacional, proporcionando uma experiência verdadeiramente nativa através da web."
          ]
        },
      ]
    },
    {
      id: "analise-consultoria",
      title: "Análise e Consultoria",
      videoSrc: "assets/videos/tab_video002.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
      ),
      items: [
        {
          name: "Levantamento de requisitos",
          description: [
            "O levantamento de requisitos é uma etapa fundamental no desenvolvimento de software, onde identificamos e documentamos todas as necessidades, expectativas e restrições do projeto. Este processo envolve reuniões detalhadas com stakeholders, análise de processos existentes e documentação completa de requisitos funcionais e não-funcionais.",
            "Utilizamos técnicas modernas de elicitação de requisitos, incluindo workshops, entrevistas, prototipagem rápida e análise de sistemas legados. Cada requisito é priorizado, validado e documentado de forma clara, garantindo que todos os envolvidos tenham uma visão compartilhada do produto final.",
            "Ao final desta etapa, entregaremos documentação completa incluindo casos de uso, histórias de usuário, diagramas de fluxo e especificações técnicas. Este trabalho minucioso garante que o desenvolvimento seja direcionado, eficiente e alinhado com as reais necessidades do negócio."
          ]
        },
        {
          name: "Análise de viabilidade técnica",
          description: [
            "A análise de viabilidade técnica avalia se a solução proposta é tecnicamente possível e viável de ser implementada. Analisamos tecnologias disponíveis, restrições técnicas, integrações necessárias e recursos requeridos para determinar a melhor abordagem para o projeto.",
            "Realizamos estudos de mercado, análise de concorrentes, avaliação de custos e benefícios, e identificação de riscos técnicos. Esta análise profunda permite tomar decisões informadas sobre arquitetura, tecnologias e metodologias a serem adotadas.",
            "O resultado é um documento detalhado com recomendações técnicas, estimativas de prazo e custo, identificação de riscos potenciais e plano de mitigação. Esta análise crítica evita investimentos em soluções inviáveis e garante escolhas tecnológicas adequadas."
          ]
        },
        {
          name: "Modelagem de processos de negócio",
          description: [
            "A modelagem de processos de negócio envolve o mapeamento detalhado de todos os processos organizacionais, identificando fluxos de trabalho, pontos de decisão, responsabilidades e oportunidades de melhoria. Utilizamos notações padrão como BPMN para criar representações visuais claras dos processos.",
            "Este trabalho permite identificar gargalos, redundâncias e ineficiências nos processos atuais, propondo otimizações e automações que aumentem a produtividade e reduzam custos. Cada processo é analisado em detalhes, considerando diferentes cenários e exceções.",
            "Entregaremos documentação completa dos processos modelados, incluindo diagramas, descrições detalhadas, métricas de performance e recomendações de melhorias. Esta modelagem serve como base fundamental para o desenvolvimento de sistemas que realmente automatizam e otimizam o negócio."
          ]
        },
        {
          name: "Auditoria de sistemas existentes",
          description: [
            "A auditoria de sistemas existentes é uma análise profunda e abrangente da infraestrutura tecnológica atual, identificando problemas, vulnerabilidades, pontos de melhoria e oportunidades de otimização. Avaliamos código, arquitetura, segurança, performance e aderência às melhores práticas.",
            "Durante a auditoria, revisamos documentação técnica, realizamos análises de código estático e dinâmico, testes de segurança, avaliação de performance e entrevistas com equipes técnicas. Cada aspecto do sistema é minuciosamente examinado e documentado.",
            "O resultado é um relatório detalhado com diagnóstico completo do estado atual, identificação de problemas críticos e não-críticos, recomendações priorizadas de melhorias e plano de ação. Esta auditoria fornece insights valiosos para decisões estratégicas sobre manutenção, evolução ou substituição de sistemas."
          ]
        },
        {
          name: "Consultoria em arquitetura de software",
          description: [
            "A consultoria em arquitetura de software oferece orientação especializada para definir a estrutura técnica mais adequada para seu projeto. Analisamos requisitos, restrições e objetivos para propor arquiteturas escaláveis, manuteníveis e alinhadas com as melhores práticas da indústria.",
            "Consideramos aspectos como microserviços vs monolito, escolha de tecnologias, padrões de design, estratégias de integração, gerenciamento de dados e segurança. Cada decisão arquitetural é justificada com base em análise técnica e alinhamento com objetivos de negócio.",
            "Entregaremos documentação arquitetural completa, incluindo diagramas C4, ADRs (Architecture Decision Records), guias de desenvolvimento e recomendações de ferramentas. Nossa consultoria garante que seu sistema seja construído sobre fundações sólidas que suportarão crescimento e evolução futuros."
          ]
        },
      ]
    },

    {
      id: "integracao-apis",
      title: "Integração e APIs",
      videoSrc: "assets/videos/tab_video003.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      ),
      items: [
        {
          name: "Desenvolvimento de APIs REST/GraphQL",
          description: [
            "O desenvolvimento de APIs REST e GraphQL envolve a criação de interfaces de programação robustas e bem documentadas que permitem a comunicação eficiente entre sistemas. Utilizamos padrões da indústria para garantir que as APIs sejam escaláveis, seguras e fáceis de integrar. REST é ideal para operações CRUD simples e arquiteturas tradicionais, enquanto GraphQL oferece flexibilidade para consultas complexas e redução de over-fetching de dados.",
            "Implementamos versionamento de APIs, autenticação via OAuth 2.0 ou JWT, rate limiting para prevenir abusos, e documentação interativa usando Swagger/OpenAPI ou GraphQL Playground. Cada endpoint é cuidadosamente projetado seguindo princípios RESTful ou convenções GraphQL, com tratamento adequado de erros, validação de dados e respostas padronizadas.",
            "Além da implementação técnica, fornecemos documentação completa com exemplos de uso, guias de integração e suporte durante a fase de adoção. As APIs desenvolvidas são testadas rigorosamente para garantir performance, segurança e conformidade com os requisitos do negócio, preparadas para suportar alto volume de requisições e crescimento futuro."
          ]
        },
        {
          name: "Integração entre sistemas legados e modernos",
          description: [
            "A integração entre sistemas legados e modernos é um desafio técnico que requer experiência e conhecimento profundo de diferentes arquiteturas e tecnologias. Desenvolvemos soluções que permitem a comunicação eficiente entre sistemas antigos (mainframes, COBOL, AS400) e aplicações modernas (cloud-native, microserviços, APIs REST), preservando investimentos existentes enquanto possibilitam a evolução tecnológica.",
            "Utilizamos padrões de integração empresarial (Enterprise Integration Patterns), middleware de integração, message brokers (RabbitMQ, Kafka), e ferramentas ETL quando necessário. Criamos adaptadores customizados, wrappers e camadas de abstração que traduzem protocolos antigos para formatos modernos, garantindo compatibilidade sem necessidade de reescrever sistemas legados.",
            "Todo o processo inclui mapeamento detalhado de dados, transformação de formatos, sincronização em tempo real ou batch, tratamento de exceções e monitoramento contínuo. Garantimos que a integração seja transparente, confiável e minimize riscos operacionais, permitindo que a organização modernize sua infraestrutura de forma gradual e controlada."
          ]
        },
        {
          name: "Integração com serviços de terceiros (pagamento, ERP, CRM)",
          description: [
            "A integração com serviços de terceiros é essencial para sistemas modernos que precisam conectar-se a plataformas externas como gateways de pagamento (Stripe, PayPal, PagSeguro), ERPs (SAP, TOTVS, Oracle), CRMs (Salesforce, HubSpot, RD Station) e outras ferramentas especializadas. Desenvolvemos conectores robustos que gerenciam autenticação, sincronização de dados e tratamento de webhooks.",
            "Implementamos lógica de retry, circuit breakers para falhas temporárias, filas para processamento assíncrono e logs detalhados para auditoria e troubleshooting. Cada integração é testada extensivamente em ambientes de sandbox antes de entrar em produção, garantindo que transações críticas como pagamentos sejam processadas com segurança e confiabilidade.",
            "Fornecemos monitoramento em tempo real das integrações, alertas automáticos para falhas, dashboards de status e relatórios de sincronização. A documentação completa inclui fluxos de dados, mapeamento de campos, tratamento de erros e procedimentos de contingência, garantindo que as integrações operem continuamente sem interrupções no negócio."
          ]
        },
        {
          name: "Desenvolvimento de microserviços",
          description: [
            "O desenvolvimento de microserviços envolve a criação de aplicações distribuídas compostas por serviços pequenos, independentes e especializados que se comunicam através de APIs. Essa arquitetura oferece escalabilidade superior, facilita deploys independentes, permite uso de diferentes tecnologias por serviço e melhora a resiliência geral do sistema através do isolamento de falhas.",
            "Implementamos microserviços usando containers Docker, orquestração com Kubernetes, service mesh para gerenciamento de comunicação, API gateway para roteamento e segurança, e ferramentas de observabilidade (logs centralizados, métricas, tracing distribuído). Cada microserviço é projetado seguindo princípios de Domain-Driven Design (DDD), com boundaries bem definidos e comunicação assíncrona através de message brokers quando apropriado.",
            "Além da implementação técnica, estabelecemos práticas de CI/CD para cada serviço, estratégias de versionamento, testes automatizados (unitários, integração, contratos) e documentação de APIs. A arquitetura de microserviços desenvolvida permite que equipes trabalhem independentemente, acelerando o desenvolvimento e facilitando a evolução contínua do sistema."
          ]
        },
        {
          name: "Web Services (SOAP/REST)",
          description: [
            "O desenvolvimento de Web Services SOAP e REST atende diferentes necessidades de integração empresarial. SOAP (Simple Object Access Protocol) é ideal para ambientes corporativos que exigem contratos rígidos, transações distribuídas e segurança WS-Security, enquanto REST oferece simplicidade, leveza e é amplamente adotado para integrações modernas baseadas em HTTP/JSON.",
            "Para Web Services SOAP, implementamos WSDLs bem definidos, validação de schemas XML, handlers para autenticação e criptografia, e suporte a padrões WS-* (WS-Security, WS-ReliableMessaging, WS-Transaction). Para REST, seguimos princípios RESTful, utilizamos versionamento de API, implementamos HATEOAS quando apropriado e garantimos stateless communication para melhor escalabilidade.",
            "Fornecemos ferramentas de teste (SoapUI, Postman), documentação detalhada (WSDL para SOAP, OpenAPI para REST), exemplos de integração em múltiplas linguagens e suporte técnico durante a implementação. Ambas as abordagens são desenvolvidas com foco em performance, segurança, monitoramento e facilidade de consumo pelos clientes."
          ]
        },
      ]
    },

    {
      id: "banco-dados",
      title: "Banco de Dados",
      videoSrc: "assets/videos/tab_video004.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
        </svg>
      ),
      items: [
        {
          name: "Modelagem e design de banco de dados",
          description: [
            "A modelagem e design de banco de dados é o processo fundamental de estruturar informações de forma lógica e eficiente. Criamos modelos conceituais (entidade-relacionamento), lógicos (normalização, definição de chaves) e físicos (otimização para SGBD específico) que garantem integridade referencial, eliminam redundâncias e suportam os requisitos do negócio de forma escalável.",
            "Aplicamos técnicas de normalização (até 3FN ou BCNF quando apropriado) ou desnormalização estratégica para performance, definimos índices adequados, constraints, triggers e stored procedures quando necessário. Para bancos NoSQL, modelamos documentos, coleções e relacionamentos considerando padrões de acesso e requisitos de consistência eventual.",
            "Entregaremos diagramas ER completos, dicionário de dados detalhado, scripts DDL documentados e estratégias de particionamento quando aplicável. A modelagem considera crescimento futuro, facilidade de manutenção e suporte a evolução do esquema sem downtime, garantindo que o banco de dados seja uma base sólida para o sistema."
          ]
        },
        {
          name: "Otimização e performance",
          description: [
            "A otimização e performance de banco de dados envolve análise profunda de queries, índices, planos de execução e configurações do SGBD para identificar e eliminar gargalos. Utilizamos ferramentas de profiling, análise de slow query logs, explain plans e métricas de I/O para diagnosticar problemas e implementar melhorias que podem resultar em ganhos de performance de 10x a 100x.",
            "Implementamos índices compostos estratégicos, views materializadas para consultas complexas, particionamento de tabelas grandes, tuning de parâmetros do SGBD (buffers, cache, connections), e otimização de queries através de reescrita, uso apropriado de joins e subqueries. Para sistemas de alto volume, implementamos read replicas, cache (Redis, Memcached) e estratégias de sharding quando necessário.",
            "Fornecemos relatórios detalhados de antes/depois, recomendações de hardware quando aplicável, scripts de manutenção automatizada (vacuum, reindex, analyze) e monitoramento contínuo de performance. O resultado é um banco de dados que opera com máxima eficiência, responde rapidamente mesmo sob alta carga e suporta crescimento sem degradação de performance."
          ]
        },
        {
          name: "Migração de dados",
          description: [
            "A migração de dados é um processo crítico que envolve transferir informações entre sistemas, bancos de dados ou formatos diferentes mantendo integridade, consistência e minimizando downtime. Desenvolvemos estratégias de migração que incluem análise de dados origem, mapeamento para destino, transformações necessárias, validações e rollback plans para garantir sucesso da operação.",
            "Utilizamos ferramentas ETL (Extract, Transform, Load) customizadas ou plataformas especializadas, implementamos migração incremental para grandes volumes, validação de dados em cada etapa e sincronização bidirecional quando necessário para permitir rollback seguro. Todo processo é testado exaustivamente em ambientes de staging antes de executar em produção.",
            "Fornecemos documentação completa do processo de migração, scripts automatizados, logs detalhados para auditoria, relatórios de validação comparando origem/destino e planos de contingência. Garantimos zero perda de dados, mínimo downtime e transição suave, com suporte especializado durante todo o processo e período pós-migração."
          ]
        },
        {
          name: "Administração e manutenção",
          description: [
            "A administração e manutenção de banco de dados engloba todas as atividades necessárias para manter o sistema operando com segurança, performance e disponibilidade. Isso inclui backups automatizados com testes regulares de restore, monitoramento 24/7 de recursos e performance, aplicação de patches de segurança, gerenciamento de usuários e permissões, e tuning contínuo conforme o sistema evolui.",
            "Implementamos rotinas de manutenção automatizadas (vacuum, reindex, analyze, statistics update), estratégias de backup incremental e full, disaster recovery plans testados regularmente, alta disponibilidade através de replicação ou clustering, e monitoramento proativo que identifica problemas antes que impactem usuários. Gerenciamos crescimento de dados através de arquivamento ou particionamento.",
            "Fornecemos SLAs claros, documentação de procedimentos operacionais, dashboards de monitoramento em tempo real, relatórios mensais de saúde do banco, e equipe de plantão para incidentes críticos. A administração profissional garante que o banco de dados seja confiável, seguro e opere com máxima eficiência continuamente."
          ]
        },
        {
          name: "Business Intelligence e Data Warehouse",
          description: [
            "Business Intelligence (BI) e Data Warehouse envolvem a criação de infraestrutura analítica que consolida dados de múltiplas fontes para análise estratégica e tomada de decisão. Desenvolvemos data warehouses usando modelagem dimensional (star schema, snowflake), ETL robusto para integração de dados, e ferramentas de visualização (Power BI, Tableau, Metabase) que transformam dados brutos em insights acionáveis.",
            "Implementamos processos ETL que extraem dados de sistemas transacionais, aplicam transformações e limpezas, calculam métricas e KPIs, e carregam no data warehouse mantendo histórico completo para análises temporais. Criamos cubos OLAP, agregações pré-calculadas para performance, e garantimos qualidade dos dados através de validações rigorosas e processos de data quality.",
            "Desenvolvemos dashboards interativos, relatórios automatizados, alertas baseados em métricas e modelos de machine learning para análises preditivas quando aplicável. Toda a solução é documentada, inclui governança de dados, controles de acesso granulares e é projetada para evoluir conforme novas fontes de dados e necessidades analíticas surgem."
          ]
        },
      ]
    },

    {
      id: "manutencao-suporte",
      title: "Manutenção e Suporte",
      videoSrc: "assets/videos/tab_video005.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      ),
      items: [
        {
          name: "Manutenção corretiva, evolutiva e adaptativa",
          description: [
            "A manutenção de software engloba três tipos essenciais: corretiva (correção de bugs), evolutiva (adição de novas funcionalidades) e adaptativa (adequação a mudanças de ambiente). Oferecemos serviços completos de manutenção que garantem que o sistema continue operando corretamente, evolua conforme necessidades do negócio e se adapte a mudanças tecnológicas, regulatórias ou de infraestrutura.",
            "Para manutenção corretiva, utilizamos ferramentas de bug tracking, análise de root cause, testes de regressão automatizados e deploys controlados com rollback plans. Na manutenção evolutiva, seguimos processo formal de levantamento de requisitos, priorização, desenvolvimento incremental e validação com usuários. Manutenções adaptativas incluem upgrades de dependências, migração para novas versões de frameworks e adequação a mudanças de plataforma.",
            "Fornecemos SLA definido por criticidade de issues, comunicação transparente sobre status de correções, documentação de todas as alterações e releases notes detalhadas. A manutenção é planejada para minimizar impacto nos usuários, com janelas de manutenção programadas, comunicação prévia e equipe disponível para suporte pós-deploy."
          ]
        },
        {
          name: "Suporte técnico especializado",
          description: [
            "O suporte técnico especializado oferece assistência qualificada para resolução de problemas, dúvidas técnicas e orientações sobre uso do sistema. Estruturamos níveis de suporte (L1, L2, L3) com escalação apropriada, SLAs por criticidade de chamados, múltiplos canais de atendimento (ticket, email, telefone, chat) e equipe técnica capacitada com conhecimento profundo do sistema.",
            "Utilizamos plataformas de help desk para gerenciamento de chamados, base de conhecimento para soluções comuns, scripts de troubleshooting para diagnóstico rápido e ferramentas de acesso remoto quando necessário. Cada interação é documentada, permitindo análise de problemas recorrentes e identificação de oportunidades de melhoria no sistema ou documentação.",
            "Fornecemos relatórios periódicos de chamados (volume, tempo de resolução, categorias), análises de tendências, recomendações proativas e treinamentos para reduzir dependência de suporte. O objetivo é não apenas resolver problemas rapidamente, mas também capacitar usuários e melhorar continuamente a qualidade e usabilidade do sistema."
          ]
        },
        {
          name: "Monitoramento de sistemas",
          description: [
            "O monitoramento de sistemas é essencial para garantir disponibilidade, performance e identificação proativa de problemas antes que impactem usuários. Implementamos monitoramento em múltiplas camadas: infraestrutura (CPU, memória, disco, rede), aplicação (response time, error rates, throughput), negócio (transações, conversões) e experiência do usuário (real user monitoring, synthetic monitoring).",
            "Utilizamos ferramentas modernas como Prometheus, Grafana, ELK Stack, New Relic ou Datadog para coleta de métricas, logs centralizados e tracing distribuído. Configuramos alertas inteligentes com thresholds apropriados, escalação automática, dashboards em tempo real e on-call rotations para garantir resposta rápida a incidentes críticos 24/7.",
            "Fornecemos dashboards customizados por stakeholder, relatórios de uptime e performance, análises de capacity planning, post-mortems detalhados de incidentes e recomendações contínuas de otimização. O monitoramento não é apenas reativo, mas proativo, identificando tendências e problemas potenciais antes que causem impacto no negócio."
          ]
        },
        {
          name: "Gestão de incidentes",
          description: [
            "A gestão de incidentes estabelece processos estruturados para responder, resolver e aprender com falhas ou degradações de serviço. Seguimos frameworks ITIL/ITSM com classificação de severidade, procedimentos de escalação, comunicação com stakeholders e post-mortems blameless que focam em melhorias sistêmicas em vez de culpabilização individual.",
            "Mantemos runbooks atualizados para incidentes comuns, equipes on-call treinadas, war rooms virtuais para incidentes críticos, e ferramentas de comunicação dedicadas (PagerDuty, Opsgenie). Cada incidente é documentado com timeline detalhada, ações tomadas, root cause analysis e action items para prevenir recorrência.",
            "Fornecemos métricas como MTTR (Mean Time To Repair), MTBF (Mean Time Between Failures), análises de tendências de incidentes e relatórios executivos. O objetivo é não apenas resolver incidentes rapidamente, mas criar cultura de aprendizado contínuo que torna o sistema progressivamente mais resiliente e confiável."
          ]
        },
        {
          name: "Documentação técnica",
          description: [
            "A documentação técnica é fundamental para manutenibilidade, transferência de conhecimento e onboarding de novos desenvolvedores. Criamos documentação abrangente incluindo arquitetura do sistema, decisões técnicas (ADRs), guias de desenvolvimento, APIs, deployment procedures, troubleshooting guides e operational runbooks, tudo mantido atualizado e versionado junto ao código.",
            "Utilizamos ferramentas modernas como Confluence, Notion, GitBook ou documentação as code (Markdown no repositório), diagramas C4 para arquitetura, OpenAPI/Swagger para APIs, e README files bem estruturados em cada repositório. A documentação é escrita pensando em diferentes audiências (desenvolvedores, operações, produto, executivos) com níveis apropriados de detalhe.",
            "Estabelecemos processos para manter documentação atualizada, incluindo reviews em pull requests, documentação como requisito em definition of done, e revisões periódicas. Boa documentação reduz dependência de pessoas específicas, acelera onboarding, facilita manutenção e serve como single source of truth para decisões técnicas e operacionais."
          ]
        },
      ]
    },

    {
      id: "devops-infraestrutura",
      title: "DevOps e Infraestrutura",
      videoSrc: "assets/videos/tab_video006.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
        </svg>
      ),
      items: [
        {
          name: "Configuração de servidores e cloud",
          description: [
            "A configuração de servidores e cloud envolve o provisionamento, hardening e otimização de infraestrutura tanto on-premise quanto em nuvem (AWS, Azure, GCP). Implementamos Infrastructure as Code (IaC) usando Terraform, CloudFormation ou Pulumi para criar ambientes reproduzíveis, versionados e auditáveis. Cada recurso é configurado seguindo security best practices, princípio de menor privilégio e segregação de ambientes (dev, staging, produção).",
            "Configuramos auto-scaling para lidar com variações de carga, load balancers para distribuição de tráfego, firewalls e security groups, VPNs para acesso seguro, e monitoring/alerting desde o início. Para servidores, aplicamos hardening guidelines (CIS Benchmarks), atualizações automatizadas de segurança, configuração de logs centralizados e backup automatizado.",
            "Fornecemos documentação completa da arquitetura, diagramas de rede, runbooks operacionais, disaster recovery procedures e análises de custo com recomendações de otimização. A infraestrutura é projetada para ser escalável, segura, resiliente e custo-efetiva, com capacidade de crescer conforme as necessidades do negócio evoluem."
          ]
        },
        {
          name: "Implementação de CI/CD",
          description: [
            "A implementação de CI/CD (Continuous Integration/Continuous Deployment) automatiza o processo de build, teste e deploy, permitindo entregas mais frequentes, confiáveis e com menos erros manuais. Configuramos pipelines usando ferramentas como Jenkins, GitLab CI, GitHub Actions ou CircleCI que executam testes automatizados, análise de código, security scans e deployment automatizado para múltiplos ambientes.",
            "Os pipelines incluem stages bem definidos (lint, unit tests, integration tests, security scanning, build, deploy to staging, smoke tests, deploy to production), gates de qualidade que impedem merge de código com problemas, e estratégias de deployment como blue-green, canary ou rolling updates para minimizar riscos. Implementamos rollback automático em caso de falhas detectadas por health checks.",
            "Fornecemos dashboards de status de builds, métricas de deployment frequency e lead time, documentação de pipelines e treinamento para equipes. CI/CD não é apenas automação técnica, mas mudança cultural que aumenta confiança em deploys, reduz tempo de feedback e permite iteração rápida com qualidade."
          ]
        },
        {
          name: "Containerização (Docker/Kubernetes)",
          description: [
            "A containerização com Docker e orquestração com Kubernetes revoluciona como aplicações são desenvolvidas, deployadas e escaladas. Criamos imagens Docker otimizadas (multi-stage builds, minimal base images), configuramos Kubernetes clusters (EKS, GKE, AKS ou self-managed), e implementamos resources definitions (deployments, services, ingresses, configmaps, secrets) seguindo melhores práticas de segurança e performance.",
            "Implementamos service mesh (Istio, Linkerd) para comunicação segura entre serviços, operators para gerenciamento de stateful applications, Helm charts para deployment reproducível, horizontal pod autoscaling baseado em métricas customizadas, e strategies de resource allocation (requests/limits) para utilização eficiente de recursos. Configuramos persistent volumes, secrets management integrado (Vault, Sealed Secrets) e network policies para isolamento.",
            "Fornecemos clusters production-ready com monitoring (Prometheus/Grafana), logging (EFK stack), tracing (Jaeger), GitOps workflows (ArgoCD, Flux), documentação completa e runbooks operacionais. A containerização não é apenas tecnologia, mas metodologia que permite desenvolvimento consistente, deployments confiáveis e operação eficiente em escala."
          ]
        },
        {
          name: "Automação de deploys",
          description: [
            "A automação de deploys elimina processos manuais propensos a erros, permite deployments frequentes e confiáveis, e reduz drasticamente o tempo entre desenvolvimento e produção. Implementamos pipelines de deployment totalmente automatizados que incluem validações pré-deploy, backups automáticos, deployment incremental, health checks, smoke tests pós-deploy e rollback automático em caso de falhas.",
            "Utilizamos estratégias avançadas como blue-green deployments (dois ambientes idênticos, switch instantâneo), canary releases (deploy gradual com análise de métricas), feature flags para controle fino de funcionalidades e A/B testing. Cada deployment é rastreável, versionado e auditável, com capacidade de rollback para qualquer versão anterior em minutos.",
            "Fornecemos dashboards de deployment metrics (frequency, lead time, failure rate, recovery time), notificações automáticas em canais relevantes (Slack, Teams), documentação de procedures e disaster recovery plans. A automação de deploys aumenta confiança, reduz stress operacional e permite que equipes foquem em desenvolvimento de valor em vez de processos manuais repetitivos."
          ]
        },
        {
          name: "Backup e disaster recovery",
          description: [
            "Backup e disaster recovery são essenciais para proteção contra perda de dados, falhas de hardware, desastres naturais ou ataques cibernéticos. Implementamos estratégias de backup em múltiplas camadas (databases, file systems, configurations, code repositories) com retenção apropriada, testes regulares de restore, e armazenamento geograficamente distribuído seguindo regra 3-2-1 (3 cópias, 2 mídias diferentes, 1 offsite).",
            "Desenvolvemos disaster recovery plans (DRP) documentados com RTOs (Recovery Time Objective) e RPOs (Recovery Point Objective) definidos por criticidade de sistemas, procedimentos passo-a-passo para diferentes cenários, equipes designadas e treinadas, e disaster recovery drills periódicos para validar procedimentos. Implementamos hot standby, warm standby ou cold standby conforme requisitos de disponibilidade e budget.",
            "Fornecemos documentação completa de procedures, dashboards de status de backups, relatórios de testes de restore, análises de gaps e recomendações de melhorias. O objetivo não é apenas ter backups, mas garantir capacidade comprovada de recuperação rápida e completa do ambiente produtivo em caso de desastre, minimizando impacto no negócio."
          ]
        },
      ]
    },

    {
      id: "seguranca",
      title: "Segurança",
      videoSrc: "assets/videos/tab_video007.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
      ),
      items: [
        {
          name: "Auditoria de segurança",
          description: [
            "A auditoria de segurança é uma avaliação abrangente de sistemas, código, infraestrutura e processos para identificar vulnerabilidades, riscos e não-conformidades com padrões de segurança. Realizamos análise de código fonte (SAST), testes de penetração (pentesting), revisão de configurações de servidores e cloud, análise de logs de segurança, e avaliação de políticas de acesso e governança de dados.",
            "Utilizamos ferramentas automatizadas (SonarQube, Snyk, OWASP ZAP) combinadas com análise manual especializada, seguindo frameworks como OWASP Top 10, CIS Benchmarks, NIST Cybersecurity Framework e ISO 27001. Avaliamos aspectos como autenticação, autorização, criptografia, input validation, session management, error handling, logging, e proteção contra ataques comuns (SQL injection, XSS, CSRF, etc).",
            "Fornecemos relatório detalhado com findings classificados por severidade (crítico, alto, médio, baixo), evidências técnicas, impacto potencial, recomendações priorizadas de remediação e roadmap de implementação. A auditoria não é apenas técnica, mas também avalia processos de desenvolvimento seguro, awareness de equipes e cultura de segurança da organização."
          ]
        },
        {
          name: "Implementação de autenticação e autorização",
          description: [
            "A implementação de autenticação e autorização robusta é fundamental para proteger sistemas e dados sensíveis. Desenvolvemos soluções que incluem autenticação multi-fator (MFA), single sign-on (SSO) via OAuth 2.0/OpenID Connect, integração com provedores de identidade (Active Directory, LDAP, SAML), e gerenciamento granular de permissões baseado em roles (RBAC) ou atributos (ABAC).",
            "Implementamos session management seguro com tokens JWT, refresh tokens, token rotation, proteção contra session hijacking e fixation, e logout em todos os dispositivos. Para autorização, criamos policies bem definidas, princípio de menor privilégio, segregação de funções (SoD), audit trails completos de acessos e alterações, e revisões periódicas de permissões para evitar privilege creep.",
            "Fornecemos interfaces administrativas para gerenciamento de usuários e permissões, logs detalhados de autenticações e autorizações, alertas para tentativas suspeitas, documentação de políticas de acesso e compliance com regulamentações como LGPD/GDPR. A segurança não é apenas tecnologia, mas processos e governança adequados."
          ]
        },
        {
          name: "Proteção contra vulnerabilidades",
          description: [
            "A proteção contra vulnerabilidades envolve múltiplas camadas de defesa: prevenção durante desenvolvimento (secure coding practices, code reviews), detecção através de ferramentas automatizadas (SAST, DAST, dependency scanning), e resposta rápida a vulnerabilidades descobertas. Implementamos input validation rigorosa, output encoding, parameterized queries para prevenir SQL injection, proteção CSRF, headers de segurança HTTP, e sanitização de dados em todas as camadas.",
            "Utilizamos WAF (Web Application Firewall) para proteção em runtime, rate limiting e CAPTCHA para proteção contra bots, DDoS protection, secrets management adequado (nunca hardcoded), criptografia em trânsito (TLS 1.3) e em repouso, e atualizações regulares de dependências para corrigir vulnerabilidades conhecidas. Implementamos security scanning em pipelines CI/CD que bloqueia deployments com vulnerabilidades críticas.",
            "Fornecemos vulnerability management program com inventário de assets, scanning regular, priorização baseada em risco, SLAs de remediação por severidade, métricas de security posture e relatórios executivos. Mantemos subscrição a security advisories, participamos de bug bounty programs quando apropriado e temos incident response plan para resposta rápida a exploits."
          ]
        },
        {
          name: "Conformidade com LGPD/GDPR",
          description: [
            "A conformidade com LGPD (Lei Geral de Proteção de Dados) e GDPR (General Data Protection Regulation) é mandatória para organizações que processam dados pessoais. Implementamos Privacy by Design e Privacy by Default, mapeamento de dados pessoais (data mapping), classificação de sensibilidade, minimização de coleta, bases legais adequadas para processamento, e mechanisms para exercício de direitos dos titulares (acesso, retificação, exclusão, portabilidade, oposição).",
            "Desenvolvemos sistemas de consent management com granularidade apropriada, registro de consentimentos auditável, privacy notices claras, data retention policies automatizadas, anonimização/pseudonimização quando aplicável, e Data Protection Impact Assessments (DPIA) para processamentos de alto risco. Implementamos security controls adequados (criptografia, access controls, audit logs) e processos de data breach notification.",
            "Fornecemos documentação completa de compliance incluindo inventário de dados, fluxos de processamento, bases legais, relatórios de conformidade, evidências para autoridades regulatórias, treinamentos para equipes e procedimentos de resposta a requisições de titulares. A conformidade não é apenas legal, mas constrói confiança com clientes e diferencial competitivo."
          ]
        },
        {
          name: "Testes de penetração",
          description: [
            "Os testes de penetração (pentesting) simulam ataques reais para identificar vulnerabilidades exploráveis antes que atacantes maliciosos as descubram. Realizamos pentests seguindo metodologias reconhecidas (OWASP, PTES, NIST) em escopo definido (black-box, white-box ou grey-box) cobrindo aplicações web, APIs, mobile apps, infraestrutura de rede e sistemas internos, com autorização formal e contratos de confidencialidade.",
            "O processo inclui reconnaissance (coleta de informações públicas), scanning (identificação de serviços e vulnerabilidades), exploitation (tentativa de explorar vulnerabilidades identificadas respeitando limites acordados), post-exploitation (avaliar impacto e pivoting possível), e reporting. Utilizamos ferramentas automatizadas e técnicas manuais especializadas, sempre priorizando não causar impacto em produção.",
            "Fornecemos relatório executivo e técnico detalhado com vulnerabilidades encontradas, evidências (screenshots, requests/responses), severidade baseada em CVSS, impacto potencial, passos para reprodução, recomendações priorizadas de remediação e re-testing após correções. Pentests regulares (anuais ou após mudanças significativas) são essenciais para manter postura de segurança robusta."
          ]
        },
      ]
    },

    {
      id: "outros-servicos",
      title: "Outros Serviços",
      videoSrc: "assets/videos/tab_video008.mp4",
      icon: (
        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      ),
      items: [
        {
          name: "Prototipagem e MVP",
          description: [
            "A prototipagem e desenvolvimento de MVP (Minimum Viable Product) permite validar ideias rapidamente com investimento mínimo, testando hipóteses de negócio antes de comprometer recursos significativos. Criamos protótipos interativos (Figma, Adobe XD) ou MVPs funcionais focados nas funcionalidades core que demonstram valor para early adopters e geram feedback real de usuários.",
            "Utilizamos metodologias ágeis e lean startup, priorizando rigorosamente features através de frameworks como MoSCoW (Must, Should, Could, Won't), desenvolvendo em sprints curtos com demonstrações frequentes. O MVP inclui apenas funcionalidades essenciais para validar a proposta de valor, com arquitetura que permite evolução futura sem reescritas completas.",
            "Fornecemos métricas de validação pré-definidas, ferramentas de analytics para medir engagement e conversão, iterações rápidas baseadas em feedback de usuários, e roadmap de evolução pós-validação. A abordagem de MVP reduz time-to-market, minimiza riscos de investimento e permite pivots rápidos baseados em dados reais de mercado."
          ]
        },
        {
          name: "Migração de sistemas legados",
          description: [
            "A migração de sistemas legados envolve modernizar aplicações antigas para tecnologias atuais mantendo continuidade de negócio. Avaliamos o sistema legado (tecnologias, arquitetura, dependências, debt técnico), definimos estratégia de migração (rewrite, refactor, re-platform, lift-and-shift) e executamos em fases incrementais para minimizar riscos e permitir rollback se necessário.",
            "Para migrações complexas, utilizamos Strangler Fig Pattern (substituição gradual de funcionalidades), anti-corruption layers para integração temporária entre sistemas novo/legado, dual-write/dual-read para validação, e feature flags para controlar rollout. Preservamos dados históricos através de migração cuidadosa, garantimos paridade funcional e mantemos usuários informados durante transição.",
            "Fornecemos análise de gap funcional, planos de migração detalhados com milestones, documentação de mapeamentos de funcionalidades, treinamento de usuários nas novas interfaces, período de suporte híbrido (legado+novo) e eventual descomissionamento do sistema antigo. A migração não é apenas técnica, mas gerenciamento de mudança organizacional."
          ]
        },
        {
          name: "Automatização de processos (RPA)",
          description: [
            "A automatização de processos através de RPA (Robotic Process Automation) utiliza bots de software para executar tarefas repetitivas, baseadas em regras, que atualmente consomem tempo de colaboradores. Identificamos processos candidatos (alto volume, baixa variação, orientados por regras), modelamos workflows, desenvolvemos bots usando ferramentas como UiPath, Automation Anywhere ou Blue Prism, e implementamos com governança apropriada.",
            "Os bots desenvolvidos interagem com sistemas através de interfaces gráficas (UI automation), APIs ou integrações diretas, executam validações, tomam decisões baseadas em regras de negócio, geram logs detalhados e escalam notificações quando encontram exceções que requerem intervenção humana. Implementamos exception handling robusto, retry mechanisms e human-in-the-loop para casos complexos.",
            "Fornecemos análise de ROI mostrando horas economizadas, redução de erros e aceleração de processos, dashboards de performance de bots, processos de manutenção e evolução de automações, e Center of Excellence (CoE) para governança de RPA. A automatização libera pessoas para trabalho de maior valor enquanto bots executam tarefas mundanas com precisão e velocidade."
          ]
        },
        {
          name: "Testes de software (unitários, integração, performance)",
          description: [
            "Os testes de software são essenciais para garantir qualidade, confiabilidade e performance. Implementamos estratégias abrangentes de testes incluindo unitários (funções isoladas), integração (interação entre componentes), end-to-end (fluxos completos de usuário), performance (carga, stress, spike), segurança (SAST, DAST, pentest) e aceitação (validação de requisitos). Utilizamos test pyramid para distribuição eficiente de esforço de testes.",
            "Automatizamos testes usando frameworks apropriados (JUnit, pytest, Jest, Selenium, Cypress, JMeter), integramos em pipelines CI/CD com gates de qualidade, implementamos TDD (Test-Driven Development) ou BDD (Behavior-Driven Development) quando apropriado, e mantemos coverage acima de thresholds definidos. Testes não são afterthought, mas parte integral do processo de desenvolvimento.",
            "Fornecemos relatórios de coverage, métricas de qualidade de código, dashboards de execução de testes, análises de flakiness de testes, e documentação de cenários de teste. Os testes automatizados funcionam como safety net que permite refactoring confiante, deploys frequentes e detecção precoce de regressões, melhorando significativamente a qualidade do software."
          ]
        },
        {
          name: "Treinamento de usuários",
          description: [
            "O treinamento de usuários é crucial para adoção bem-sucedida de novos sistemas ou funcionalidades. Desenvolvemos programas de treinamento customizados por perfil de usuário (básico, intermediário, avançado; por role/departamento), utilizando múltiplos formatos (presencial, online, híbrido), materiais didáticos (slides, vídeos, hands-on labs, documentação), e ambientes de sandbox para prática sem riscos.",
            "Os treinamentos são estruturados em módulos focados em workflows reais do dia-a-dia dos usuários, incluindo demonstrações práticas, exercícios guiados, troubleshooting de cenários comuns e Q&A sessions. Implementamos train-the-trainer programs para criar champions internos, office hours para suporte pós-treinamento, e avaliações para medir efetividade e identificar gaps de conhecimento.",
            "Fornecemos materiais de referência (quick reference guides, FAQs, base de conhecimento, vídeos tutoriais on-demand), certificados de conclusão, métricas de adoção e proficiência, e programa de educação continuada para novas funcionalidades. O investimento em treinamento aumenta ROI do sistema, reduz resistência à mudança e maximiza utilização de capacidades disponíveis."
          ]
        },
      ]
    },

  ];

  return (
    <div className="font-['Segoe_UI',Tahoma,Geneva,Verdana,sans-serif] bg-black text-white leading-relaxed">
      <div id="services-section" className="max-w-[1400px] mx-auto pb-16 px-5">
        <div className="grid grid-cols-1 md:grid-cols-[repeat(auto-fit,minmax(350px,1fr))] gap-10 mt-5">
          {services.map((service, index) => (
            <ServiceCard
              key={index}
              id={service.id}
              title={service.title}
              items={service.items}
              videoSrc={service.videoSrc}
              icon={service.icon}
            />
          ))}

            {/* SEÇÃO DO DESENVOLVEDOR - Card do LinkedIn */}
            <section className="py-0 px-0">
                <div className="max-w-2xl mx-auto">
                    <LinkedInProfileCard
                        name="Gustavo Hammes"
                        title="Desenvolvedor Web fullstack"
                        education="Associação Carioca de Ensino Superior"
                        website="Habilidade.Com"
                        location="Rio de Janeiro, Rio de Janeiro, Brasil"
                        connections={306}
                        jobInterest="Analista de sistema, Gerente de TI, Tecnólogo, Líder de tecnologia"
                        about="Tenho habilidades sólidas de comunicação, mantendo sempre o foco nos objetivos. Experiência em desenvolvimento full-stack com React, Node.js, PHP e muito mais."
                        profileImage="assets/imagens/linkedin.png"
                    />
                </div>
            </section>

        </div>
      </div>

    </div>
  );
}