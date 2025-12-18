import 'package:flutter/material.dart';
import '../../application/auth_controller.dart';
import '../../domain/usecases/register_user.dart';
import '../../data/repositories/auth_repository_impl.dart';
import '../../data/datasources/auth_remote_ds.dart';
import '../../../../core/config/env.dart';
import 'package:dio/dio.dart';

class RegisterCard extends StatefulWidget {
  const RegisterCard({super.key});

  @override
  State<RegisterCard> createState() => _RegisterCardState();
}

class _RegisterCardState extends State<RegisterCard> {
  final _formKey = GlobalKey<FormState>();
  final _userController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmController = TextEditingController();

  late final controller = AuthController(
    RegisterUser(
      AuthRepositoryImpl(
        AuthRemoteDataSource(dio: Dio(BaseOptions(baseUrl: Env.baseUrl))),
      ),
    ),
  );

  bool _isLoading = false;
  String? _errorMessage;
  String? _successMessage;

  // FocusNodes para UX
  final _userFocus = FocusNode();
  final _passwordFocus = FocusNode();
  final _confirmFocus = FocusNode();

  @override
  void dispose() {
    _userController.dispose();
    _passwordController.dispose();
    _confirmController.dispose();
    _userFocus.dispose();
    _passwordFocus.dispose();
    _confirmFocus.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final border = OutlineInputBorder(
      borderRadius: BorderRadius.circular(12),
      borderSide: BorderSide(color: theme.colorScheme.primary.withOpacity(0.2)),
    );
    final errorBorder = OutlineInputBorder(
      borderRadius: BorderRadius.circular(12),
      borderSide: BorderSide(color: Colors.red.shade300),
    );

    return Center(
      child: Card(
        elevation: 4,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
        margin: const EdgeInsets.symmetric(horizontal: 24, vertical: 32),
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: SizedBox(
            width: 380, // max width on desktop mode
            child: Form(
              key: _formKey,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  const Text(
                    "Usuário",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 17),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _userController,
                    focusNode: _userFocus,
                    textInputAction: TextInputAction.next,
                    decoration: InputDecoration(
                      border: border,
                      enabledBorder: border,
                      focusedBorder: border,
                      errorBorder: errorBorder,
                      hintText: "Escolha um nome de usuário",
                      prefixIcon: const Icon(Icons.person_2_rounded),
                    ),
                    validator: (v) {
                      if (v == null || v.trim().isEmpty)
                        return "Preencha o usuário";
                      if (v.length < 6) return "Mínimo 6 caracteres";
                      if (v.contains(" ")) return "Não use espaços";
                      return null;
                    },
                    onFieldSubmitted:
                        (_) =>
                            FocusScope.of(context).requestFocus(_passwordFocus),
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    "Senha",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 17),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _passwordController,
                    focusNode: _passwordFocus,
                    textInputAction: TextInputAction.next,
                    obscureText: true,
                    decoration: InputDecoration(
                      border: border,
                      enabledBorder: border,
                      focusedBorder: border,
                      errorBorder: errorBorder,
                      hintText: "Digite sua senha",
                      prefixIcon: const Icon(Icons.lock_outline_rounded),
                    ),
                    validator: (v) {
                      if (v == null || v.trim().isEmpty)
                        return "Preencha a senha";
                      if (v.length < 8) return "Mínimo 8 caracteres";
                      return null;
                    },
                    onFieldSubmitted:
                        (_) =>
                            FocusScope.of(context).requestFocus(_confirmFocus),
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    "Confirmar Senha",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 17),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: _confirmController,
                    focusNode: _confirmFocus,
                    textInputAction: TextInputAction.done,
                    obscureText: true,
                    decoration: InputDecoration(
                      border: border,
                      enabledBorder: border,
                      focusedBorder: border,
                      errorBorder: errorBorder,
                      hintText: "Confirme sua senha",
                      prefixIcon: const Icon(Icons.lock_outline_rounded),
                    ),
                    validator: (v) {
                      if (v == null || v.trim().isEmpty)
                        return "Confirme a senha";
                      if (v != _passwordController.text)
                        return "Senhas não conferem";
                      return null;
                    },
                    onFieldSubmitted: (_) => _onCadastrar(),
                  ),
                  const SizedBox(height: 24),

                  // Mensagens de erro/sucesso
                  if (_errorMessage != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(
                        _errorMessage!,
                        style: const TextStyle(color: Colors.red),
                      ),
                    ),
                  if (_successMessage != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(
                        _successMessage!,
                        style: const TextStyle(color: Colors.green),
                      ),
                    ),

                  ElevatedButton(
                    onPressed: _isLoading ? null : _onCadastrar,
                    style: ElevatedButton.styleFrom(
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(18),
                      ),
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      backgroundColor: theme.colorScheme.primary,
                    ),
                    child:
                        _isLoading
                            ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                valueColor: AlwaysStoppedAnimation<Color>(
                                  Colors.white,
                                ),
                              ),
                            )
                            : const Text(
                              "Cadastrar",
                              style: TextStyle(
                                fontSize: 17,
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  Future<void> _onCadastrar() async {
    FocusScope.of(context).unfocus(); // Fecha teclado
    setState(() {
      _errorMessage = null;
      _successMessage = null;
    });
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    final erro = await controller.registrar(
      _userController.text.trim(),
      _passwordController.text.trim(),
      _confirmController.text.trim(),
    );

    setState(() => _isLoading = false);

    if (erro == null) {
      setState(() => _successMessage = 'Usuário cadastrado com sucesso!');
      await Future.delayed(const Duration(seconds: 1));
      if (mounted) Navigator.of(context).pop();
    } else {
      setState(() => _errorMessage = erro);
    }
  }
}
