import 'package:flutter/widgets.dart';
import '../../../app/router/route_model.dart';
import './pages/project_page.dart';

final List<ModuleRoute> moduleRoutes = [
  ModuleRoute(
    path: '/project',
    builder: (BuildContext context) => const ProjectPage(),
    children: [],
  ),
];