// frontend_flutter/v1/lib/features/about/presentation/routes.dart
import 'package:frontend_flutter_v1/app/router/route_model.dart';
import 'pages/about_page.dart';

// Export obrigatório com esse nome:
final List<ModuleRoute> moduleRoutes = [
  ModuleRoute(
    path: '/about',
    builder: (ctx) => const AboutPage(),
    children: [
      // Futuras sub-rotas podem ser adicionadas aqui
      // ModuleRoute(path: 'team', builder: (ctx) => const TeamPage()),
      // ModuleRoute(path:  'history', builder: (ctx) => const HistoryPage()),
    ],
  ),
];
