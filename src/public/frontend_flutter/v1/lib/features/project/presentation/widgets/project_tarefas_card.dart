import 'package:flutter/material.dart';
import '../../../../shared/widgets/nav_card.dart';

class ProjectTarefasCard extends StatelessWidget {
  const ProjectTarefasCard({super.key});

  @override
  Widget build(BuildContext context) {
    return const NavCard(
      icon: Icons.task_alt_outlined,
      title: 'Tarefas',
      subtitle: 'Gestão de atividades',
      navigateTo: '/project/tarefas',
    );
  }
}