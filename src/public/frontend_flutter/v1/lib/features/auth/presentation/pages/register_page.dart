import 'package:flutter/material.dart';
import '../controllers/auth_controller.dart';
import '../../data/datasources/auth_remote_datasource.dart';
import '../../data/repositories/auth_repository_impl.dart';

class RegisterPage extends StatefulWidget {
  final String baseUrl;
  const RegisterPage({super.key, required this.baseUrl});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  late final AuthController controller;
  int currentStep = 0;
  int? createdUserId;

  @override
  void initState() {
    super.initState();
    final remote = AuthRemoteDataSource(baseUrl: widget.baseUrl);
    final repo = AuthRepositoryImpl(remote: remote);
    controller = AuthController(repository: repo);
  }

  @override
  void dispose() {
    controller.disposeAll();
    super.dispose();
  }

  void _showMessage(String msg) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
  }

  Future<void> _submitPart1() async {
    final user = controller.regUserController.text.trim();
    final pw = controller.regPasswordController.text;
    final pw2 = controller.regPasswordConfirmController.text;
    if (user.isEmpty || pw.isEmpty || pw2.isEmpty) {
      _showMessage('Preencha usuário e senhas');
      return;
    }
    if (pw != pw2) {
      _showMessage('As senhas não coincidem');
      return;
    }
    try {
      final userId = await controller.registerPart1();
      createdUserId = userId;
      setState(() => currentStep = 1);
      _showMessage('Usuário criado (id: $userId). Complete o perfil.');
    } catch (e) {
      _showMessage('Erro ao criar usuário: ${e.toString()}');
    }
  }

  Future<void> _submitPart2() async {
    if (createdUserId == null) {
      _showMessage('Id do usuário não encontrado. Complete parte 1 primeiro.');
      return;
    }
    if (controller.nameController.text.trim().isEmpty) {
      _showMessage('Informe o nome');
      return;
    }
    try {
      final customerId = await controller.registerPart2(userId: createdUserId!);

      if (!mounted) {
        return;
      } // <--- protege contra uso de context se State já foi disposed

      _showMessage(
        'Perfil criado (customer id: $customerId). Cadastro concluído.',
      );
      Navigator.of(context).pop();
    } catch (e) {
      if (!mounted) {
        return;
      }
      _showMessage('Erro ao completar perfil: ${e.toString()}');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Cadastro de Usuário')),
      body: Padding(
        padding: const EdgeInsets.all(12.0),
        child: currentStep == 0 ? _buildPart1() : _buildPart2(),
      ),
    );
  }

  Widget _buildPart1() {
    return Column(
      children: [
        TextField(
          controller: controller.regUserController,
          decoration: const InputDecoration(labelText: 'Usuário (login)'),
        ),
        const SizedBox(height: 8),
        TextField(
          controller: controller.regPasswordController,
          decoration: const InputDecoration(labelText: 'Senha'),
          obscureText: true,
        ),
        const SizedBox(height: 8),
        TextField(
          controller: controller.regPasswordConfirmController,
          decoration: const InputDecoration(labelText: 'Confirmar senha'),
          obscureText: true,
        ),
        const SizedBox(height: 16),
        ElevatedButton(
          onPressed: _submitPart1,
          child: const Text('Criar usuário'),
        ),
      ],
    );
  }

  Widget _buildPart2() {
    return SingleChildScrollView(
      child: Column(
        children: [
          TextField(
            controller: controller.nameController,
            decoration: const InputDecoration(labelText: 'Nome'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.profileController,
            decoration: const InputDecoration(labelText: 'Perfil'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.phoneController,
            decoration: const InputDecoration(labelText: 'Telefone'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.dateBirthController,
            decoration: const InputDecoration(
              labelText: 'Data nascimento (YYYY-MM-DD)',
            ),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.zipCodeController,
            decoration: const InputDecoration(labelText: 'CEP'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.addressController,
            decoration: const InputDecoration(labelText: 'Endereço'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.cpfController,
            decoration: const InputDecoration(labelText: 'CPF'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.whatsappController,
            decoration: const InputDecoration(labelText: 'WhatsApp'),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller.mailController,
            decoration: const InputDecoration(labelText: 'E-mail'),
          ),
          const SizedBox(height: 16),
          ElevatedButton(
            onPressed: _submitPart2,
            child: const Text('Salvar perfil'),
          ),
        ],
      ),
    );
  }
}
