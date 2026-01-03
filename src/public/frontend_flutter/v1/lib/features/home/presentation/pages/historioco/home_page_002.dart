// 1. IMPORTAÇÃO DO FLUTTER
// Importa a biblioteca Material Design (sem espaço após "package:")
import 'package:flutter/material.dart';

// Importa o widget da casinha
import '../../../../../shared/widgets/navigation_header_card.dart';

// 2. CLASSE DA PÁGINA
// StatelessWidget = widget que NÃO muda de estado
class HomePage extends StatelessWidget {
  // 3. CONSTRUTOR
  // const = objeto imutável (melhor performance)
  // super.key = identificador único do widget
  const HomePage({super.key});

  // 4. MÉTODO BUILD
  // Chamado pelo Flutter para desenhar a tela
  // Retorna um Widget
  @override
  Widget build(BuildContext context) {
    // 5. SCAFFOLD - estrutura base da página
    return Scaffold(
      // 6. COR DE FUNDO
      backgroundColor: Colors.white,

      // 7. BARRA SUPERIOR
      appBar: AppBar(title: const Text('Início')),

      // 8. CORPO DA PÁGINA
      body: const Padding(
        padding: EdgeInsets. all(12.0),
        child: Column(
          children:  [
            // Renderiza a casinha
            SizedBox(
              height: 250,
              child:  NavigationHeaderCard(),
            ),
            SizedBox(height:  12),
            // TODO: Grid de tiles virá aqui
            Expanded(
              flex: 3,
              child:  Center(child: Text('Grid de opções')),
            ),
          ],
        ),
      ),
    );
  }
}
