import 'package:flutter/material.dart';
import '../../features/home/presentation/pages/home_page.dart';
import '../../features/auth/presentation/pages/login_page.dart';
import 'app_routes.dart';
import '../config/env.dart';

class AppRouter {
  static Route<dynamic> onGenerateRoute(RouteSettings settings) {
    switch (settings.name) {
      case AppRoutes.login:
        // passa o baseUrl vindo do env
        return MaterialPageRoute(
          builder: (_) => LoginPage(baseUrl: Env.apiBaseUrl),
        );
      case AppRoutes.home:
      default:
        return MaterialPageRoute(builder: (_) => const HomePage());
    }
  }
}
