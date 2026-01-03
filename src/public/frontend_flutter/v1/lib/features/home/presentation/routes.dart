// frontend_flutter/v1/lib/features/home/presentation/routes.dart
import 'package:flutter/material.dart';
import 'package:frontend_flutter_v1/app/router/route_model.dart'; // ou import relativo se preferir
import 'pages/home_page.dart';

class SobrePage extends StatelessWidget {
  const SobrePage({super.key});
  @override
  Widget build(BuildContext context) =>
      Scaffold(body: Center(child: Text('Sobre — Quem Somos')));
}

class ServicesPage extends StatelessWidget {
  const ServicesPage({super.key});
  @override
  Widget build(BuildContext context) =>
      Scaffold(body: Center(child: Text('Serviços')));
}

class ContactPage extends StatelessWidget {
  const ContactPage({super.key});
  @override
  Widget build(BuildContext context) =>
      Scaffold(body: Center(child: Text('Fale Conosco')));
}

// Export obrigatório com esse nome:
final List<ModuleRoute> moduleRoutes = [
  ModuleRoute(
    path: '/home',
    builder: (ctx) => const HomePage(),
    children: [
      ModuleRoute(path: 'sobre', builder: (ctx) => const SobrePage()),
      ModuleRoute(path: 'servicos', builder: (ctx) => const ServicesPage()),
      ModuleRoute(path: 'fale', builder: (ctx) => const ContactPage()),
    ],
  ),
];
