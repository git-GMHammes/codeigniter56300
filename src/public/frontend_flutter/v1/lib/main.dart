import 'package:flutter/material.dart';
import 'app/routes/app_router.dart';
import 'app/routes/app_routes.dart';
import 'app/config/env.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Frontend Flutter',
      // usa o router central já implementado
      onGenerateRoute: AppRouter.onGenerateRoute,
      initialRoute: AppRoutes.login,
      // opcional: tema simples; você pode trocar por app/theme/app_theme.dart
      theme: ThemeData(
        primarySwatch: Colors.deepPurple,
        useMaterial3: true,
      ),
      debugShowCheckedModeBanner: false,
    );
  }
}