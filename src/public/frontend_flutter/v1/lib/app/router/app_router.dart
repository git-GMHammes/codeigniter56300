import 'package:flutter/material.dart';
import 'app_routes.dart';

class AppRouter {
  /// onGenerateRoute para o MaterialApp. Faz matching exato por enquanto.
  static Route<dynamic>? generateRoute(RouteSettings settings) {
    final routes = buildRouteMap();
    final name = (settings.name ?? '').trim();

    // Normalizar path (remover querystring / fragment)
    final uri = Uri.parse(name.isEmpty ? '/' : name);
    final path = uri.path.isEmpty ? '/' : uri.path;

    // Tenta encontrar o builder exatamente
    final builder = routes[path];
    if (builder != null) {
      return MaterialPageRoute(builder: builder, settings: settings);
    }

    // Poderíamos implementar matching com parâmetros (ex.: /user/:id) aqui.
    // Fallback: 404 simples
    return MaterialPageRoute(
      builder:
          (_) => Scaffold(
            appBar: AppBar(title: const Text('Página não encontrada')),
            body: Center(child: Text('Rota "$path" não encontrada.')),
          ),
      settings: settings,
    );
  }
}
