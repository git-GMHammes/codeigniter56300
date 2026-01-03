import 'package:flutter/material.dart';

class AboutTeamCard extends StatelessWidget {
  const AboutTeamCard({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    final teamMembers = [
      {'name': 'Gustavi Hammes', 'role': 'CEO & Fundador', 'initials': 'GH'},
      {'name': 'Rafael Silva', 'role':  'COO', 'initials':  'RS'},
      {'name': 'Pedro Machado', 'role':  'CTO', 'initials':  'PM'},
      {'name': 'Carlos do N Lima', 'role':  'Diretor Comercial', 'initials': 'CL'},
    ];
    
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius. circular(12)),
      child: Padding(
        padding:  const EdgeInsets. all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  Icons.people_outline,
                  color: theme.colorScheme.primary,
                  size: 28,
                ),
                const SizedBox(width: 12),
                Text(
                  'Nossa Equipe',
                  style: theme.textTheme. titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height:  20),
            
            // Lista horizontal de membros
            SizedBox(
              height: 140,
              child:  ListView.separated(
                scrollDirection: Axis. horizontal,
                itemCount: teamMembers.length,
                separatorBuilder: (_, __) => const SizedBox(width: 16),
                itemBuilder: (context, index) {
                  final member = teamMembers[index];
                  return _TeamMemberItem(
                    name: member['name']!,
                    role: member['role']!,
                    initials: member['initials']! ,
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _TeamMemberItem extends StatelessWidget {
  final String name;
  final String role;
  final String initials;
  
  const _TeamMemberItem({
    required this.name,
    required this. role,
    required this.initials,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    return SizedBox(
      width: 110,
      child:  Column(
        children: [
          CircleAvatar(
            radius: 35,
            backgroundColor:  theme.colorScheme.primaryContainer,
            child: Text(
              initials,
              style: theme.textTheme.titleLarge?.copyWith(
                color: theme.colorScheme.onPrimaryContainer,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          const SizedBox(height: 10),
          Text(
            name,
            style: theme. textTheme.bodyMedium?.copyWith(
              fontWeight: FontWeight. w600,
            ),
            textAlign: TextAlign.center,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          const SizedBox(height: 2),
          Text(
            role,
            style: theme. textTheme.bodySmall?.copyWith(
              color:  theme.colorScheme.onSurface.withOpacity(0.6),
            ),
            textAlign: TextAlign.center,
            maxLines: 2,
            overflow:  TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }
}