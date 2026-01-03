import 'package:flutter/widgets.dart';
import '../../../app/router/route_model.dart';
import 'pages/contact_us_page.dart';

final List<ModuleRoute> moduleRoutes = [
  ModuleRoute(
    path: '/contact',
    builder: (BuildContext context) => const ContactUsPage(),
    children: [],
  ),
];