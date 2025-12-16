import 'package:flutter/material.dart';
import 'app/router/app_router.dart';

void main() {
  runApp(const MyApp());
}

/// MyApp é apenas um container genérico para carregar telas via rotas.
class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Frontend Flutter',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(useMaterial3: true),
      initialRoute: '/',
      onGenerateRoute: (settings) {
        if ((settings.name ?? '') == '/') {
          return MaterialPageRoute(
            builder: (context) => const _RedirectToHome(),
            settings: settings,
          );
        }
        return AppRouter.generateRoute(settings);
      },
    );
  }
}

/// Página temporária que redireciona para '/home' assim que montada.
class _RedirectToHome extends StatefulWidget {
  // Removido o parâmetro de key não utilizado.
  const _RedirectToHome();

  @override
  State<_RedirectToHome> createState() => _RedirectToHomeState();
}

class _RedirectToHomeState extends State<_RedirectToHome> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Navigator.of(context).pushReplacementNamed('/home');
    });
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(body: Center(child: SizedBox.shrink()));
  }
}
