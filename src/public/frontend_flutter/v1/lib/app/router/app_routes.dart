// frontend_flutter/v1/lib/app/router/app_routes.dart
import 'package:flutter/widgets.dart';
import 'route_model.dart';

// importe os arquivos de rotas dos módulos (caminho relativo a lib/app/router)
import '../../features/home/presentation/routes.dart' as home_routes;
import '../../features/user/presentation/routes.dart' as user_routes;
import '../../features/about/presentation/routes.dart' as about_routes;
import '../../features/project/presentation/routes.dart' as project_routes;
import '../../features/contact_us/presentation/routes.dart' as contact_us_routes;

/// Junta as listas de cada módulo em _moduleRoutes.
/// Cada arquivo de módulo deve exportar `List<ModuleRoute> moduleRoutes`.
final List<ModuleRoute> _moduleRoutes = [
  ...home_routes.moduleRoutes,
  ...user_routes.moduleRoutes,
  ...about_routes.moduleRoutes,
  ...project_routes.moduleRoutes,
  ...contact_us_routes.moduleRoutes,
];

String _combine(String parent, String child) {
  String p = parent.trim();
  if (p.endsWith('/')) p = p.substring(0, p.length - 1);
  String c = child.trim();
  if (!c.startsWith('/')) c = '/$c';
  if (p.isEmpty) return c;
  return p + c;
}

Map<String, WidgetBuilder> buildRouteMap() {
  final map = <String, WidgetBuilder>{};

  void walk(ModuleRoute route, [String parent = '']) {
    final normalized =
        route.path.startsWith('/') ? route.path : '/${route.path}';
    final fullPath = parent.isEmpty ? normalized : _combine(parent, route.path);
    final normalizedFullPath =
        (fullPath.length > 1 && fullPath.endsWith('/'))
            ? fullPath.substring(0, fullPath.length - 1)
            : fullPath;
    map[normalizedFullPath] = route.builder;
    for (final child in route.children) {
      walk(child, normalizedFullPath);
    }
  }

  for (final r in _moduleRoutes) {
    walk(r, '');
  }
  return map;
}
