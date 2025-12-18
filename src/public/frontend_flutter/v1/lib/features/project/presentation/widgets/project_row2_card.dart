import 'package:flutter/material.dart';
import 'project_estoque_card.dart';
import 'project_tarefas_card.dart';

class ProjectRow2Card extends StatelessWidget {
  const ProjectRow2Card({super.key});

  @override
  Widget build(BuildContext context) {
    return const Row(
      children: [
        Expanded(child: ProjectEstoqueCard()),
        SizedBox(width:  12),
        Expanded(child: ProjectTarefasCard()),
      ],
    );
  }
}