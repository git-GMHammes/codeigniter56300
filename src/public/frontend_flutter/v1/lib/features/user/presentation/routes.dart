import 'package:flutter/material.dart';
import '../../../../app/router/route_model.dart';
import './pages/login_page.dart';
import './pages/register_page.dart';

final List<ModuleRoute> moduleRoutes = [
  ModuleRoute(
    path: '/user',
    builder: (ctx) => const Scaffold(body: Center(child: Text('User root'))),
    children: [
      ModuleRoute(path: 'login', builder:  (ctx) => const LoginPage()),
      ModuleRoute(path: 'register', builder: (ctx) => const RegisterPage()),
      // ModuleRoute(path:  'forgot', builder: (ctx) => const ForgotPasswordPage()),
    ],
  ),
];