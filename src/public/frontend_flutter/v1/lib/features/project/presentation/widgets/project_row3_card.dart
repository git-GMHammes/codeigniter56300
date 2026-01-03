import 'package:flutter/material.dart';
import 'project_mecanica_card.dart';
import 'project_veterinaria_card.dart';

class ProjectRow3Card extends StatelessWidget {
  const ProjectRow3Card({super.key});

  @override
  Widget build(BuildContext context) {
    return const Row(
      children: [
        Expanded(child: ProjectMecanicaCard()),
        SizedBox(width: 12),
        Expanded(child: ProjectVeterinariaCard()),
      ],
    );
  }
}
