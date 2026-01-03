import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_masked_text2/flutter_masked_text2.dart'; // Import para máscaras

/// Widget para a Etapa 2 do cadastro: Dados pessoais
class RegisterStep2Card extends StatefulWidget {
  final int userId;
  final VoidCallback onBack;
  final Future<void> Function({
    required int userId,
    required String name,
    required String cpf,
    required String mail,
    required String phone,
    required String whatsapp,
    required DateTime? dateBirth,
    required String zipCode,
    required String address,
    required File? uploadFilesPath,
  })
  onComplete;

  const RegisterStep2Card({
    super.key,
    required this.userId,
    required this.onBack,
    required this.onComplete,
  });

  @override
  State<RegisterStep2Card> createState() => _RegisterStep2CardState();
}

class _RegisterStep2CardState extends State<RegisterStep2Card> {
  final _formKey = GlobalKey<FormState>();

  late final TextEditingController _nameController;
  late final MaskedTextController _cpfController;
  late final MaskedTextController _phoneController;
  late final MaskedTextController _whatsappController;
  late final MaskedTextController _zipCodeController;
  late final TextEditingController _mailController;
  late final TextEditingController _addressController;

  File? _profileImage;
  bool _isWhatsAppCopied = false;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController();
    _cpfController = MaskedTextController(mask: '000.000.000-00');
    _phoneController = MaskedTextController(mask: '(00) 00000-0000');
    _whatsappController = MaskedTextController(mask: '(00) 00000-0000');
    _zipCodeController = MaskedTextController(mask: '00000-000');
    _mailController = TextEditingController();
    _addressController = TextEditingController();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _cpfController.dispose();
    _phoneController.dispose();
    _whatsappController.dispose();
    _zipCodeController.dispose();
    _mailController.dispose();
    _addressController.dispose();
    super.dispose();
  }

  Future<void> _handleComplete() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    await Future.delayed(const Duration(milliseconds: 300));

    // ========== INTERCEPTAÇÃO AQUI ==========
    print('==========================================');
    print('DADOS DO FORMULÁRIO (STEP 2):');
    print('userId: ${widget.userId}');
    print('name: ${_nameController.text.trim()}');
    print('cpf: ${_cpfController.text.trim()}');
    print('mail: ${_mailController.text.trim()}');
    print('phone: ${_phoneController.text.trim()}');
    print('whatsapp: ${_whatsappController.text.trim()}');
    print('zipCode: ${_zipCodeController.text.trim()}');
    print('address: ${_addressController.text.trim()}');
    print('uploadFilesPath: ${_profileImage?.path}');
    print('==========================================');

    // DESCOMENTE AS 2 LINHAS ABAIXO PARA PARAR AQUI E NÃO ENVIAR
    // setState(() => _isLoading = false);
    // return;
    // ========================================

    setState(() => _isLoading = false);

    widget.onComplete(
      userId: widget.userId,
      name: _nameController.text.trim(),
      cpf: _cpfController.text.trim(),
      mail: _mailController.text.trim(),
      phone: _phoneController.text.trim(),
      whatsapp: _whatsappController.text.trim(),
      dateBirth: null,
      zipCode: _zipCodeController.text.trim(),
      address: _addressController.text.trim(),
      uploadFilesPath: _profileImage,
    );
  }

  Future<void> _selectPhoto() async {
    final pickedFile = await ImagePicker().pickImage(
      source: ImageSource.gallery,
    );
    if (pickedFile != null) {
      setState(() {
        _profileImage = File(pickedFile.path); // ← Cria File
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return Card(
      elevation: isDark ? 2 : 4,
      shadowColor: isDark ? Colors.black45 : Colors.black26,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Header
              _buildHeader(theme),
              const SizedBox(height: 24),

              // Campos do formulário
              _buildFormFields(),
              const SizedBox(height: 24),

              // Botões de ação
              _buildActionButtons(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader(ThemeData theme) {
    return Stack(
      alignment: Alignment.center,
      children: [
        GestureDetector(
          onTap: _selectPhoto,
          child: CircleAvatar(
            radius: 48,
            backgroundColor: Colors.grey.shade200,
            child:
                _profileImage == null
                    ? Icon(Icons.person, size: 48, color: Colors.grey.shade600)
                    : ClipOval(
                      child: Image.file(
                        _profileImage!,
                        fit: BoxFit.cover,
                        width: 96,
                        height: 96,
                      ),
                    ),
          ),
        ),
        Positioned(
          bottom: 0,
          right: 0,
          child: GestureDetector(
            onTap: _selectPhoto,
            child: CircleAvatar(
              radius: 20,
              backgroundColor: Colors.white,
              child: Icon(
                Icons.camera_alt,
                size: 20,
                color: Colors.grey.shade700,
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildFormFields() {
    return Column(
      children: [
        _buildTextField(
          controller: _nameController,
          label: 'Nome Completo *',
          hint: 'Digite seu nome completo',
          icon: Icons.person,
          validator:
              (value) => value!.isEmpty ? 'Informe o nome completo' : null,
        ),
        const SizedBox(height: 16),
        _buildTextField(
          controller: _cpfController,
          label: 'CPF *',
          hint: '000.000.000-00',
          icon: Icons.badge,
          validator: (value) {
            if (value!.isEmpty || value.length < 14) return 'CPF inválido';
            return null;
          },
        ),
        const SizedBox(height: 16),
        _buildTextField(
          controller: _mailController,
          label: 'E-mail *',
          hint: 'seu@email.com',
          icon: Icons.email,
          validator: (value) {
            if (value!.isEmpty || !value.contains('@')) {
              return 'E-mail inválido';
            }
            return null;
          },
        ),
        const SizedBox(height: 16),
        _buildTextField(
          controller: _phoneController,
          label: 'Telefone *',
          hint: '(00) 00000-0000',
          icon: Icons.phone,
          validator: (value) {
            if (value!.isEmpty || value.length < 13) return 'Telefone inválido';
            return null;
          },
        ),
        const SizedBox(height: 16),
        Row(
          children: [
            const Text("Copiar telefone para WhatsApp:"),
            const SizedBox(width: 8),
            Switch(
              value: _isWhatsAppCopied,
              onChanged: (newValue) {
                setState(() {
                  _isWhatsAppCopied = newValue;
                  if (newValue) {
                    _whatsappController.text = _phoneController.text;
                  }
                });
              },
            ),
          ],
        ),
        const SizedBox(height: 16),
        _buildTextField(
          controller: _whatsappController,
          label: 'WhatsApp *',
          hint: '(00) 00000-0000',
          icon: Icons.message,
          validator: (value) {
            if (value!.isEmpty || value.length < 13) return 'WhatsApp inválido';
            return null;
          },
        ),
        const SizedBox(height: 16),
        _buildTextField(
          controller: _zipCodeController,
          label: 'CEP *',
          hint: '00000-000',
          icon: Icons.location_pin,
          validator: (value) {
            if (value!.isEmpty || value.length < 9) return 'CEP inválido';
            return null;
          },
        ),
        const SizedBox(height: 16),
        _buildTextField(
          controller: _addressController,
          label: 'Endereço *',
          hint: 'Digite seu endereço completo',
          icon: Icons.home,
        ),
      ],
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    String? hint,
    required IconData icon,
    String? Function(String?)? validator,
  }) {
    return TextFormField(
      controller: controller,
      validator: validator,
      decoration: InputDecoration(
        labelText: label,
        hintText: hint,
        prefixIcon: Icon(icon),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }

  Widget _buildActionButtons() {
    return Row(
      children: [
        Expanded(
          child: OutlinedButton.icon(
            onPressed: widget.onBack,
            icon: const Icon(Icons.arrow_back),
            label: const Text('Voltar'),
          ),
        ),
        const SizedBox(width: 16),
        Expanded(
          flex: 2,
          child: ElevatedButton.icon(
            onPressed: _isLoading ? null : _handleComplete,
            icon:
                _isLoading
                    ? const CircularProgressIndicator(strokeWidth: 2)
                    : const Icon(Icons.check),
            label: Text(_isLoading ? 'Finalizando...' : 'Finalizar Cadastro'),
          ),
        ),
      ],
    );
  }
}
