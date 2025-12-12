// src\public\script\react_modelo_v3\frontend\src\pages\FormularioBootstrap\index.jsx
import React from 'react';
import { useFormContext } from 'react-hook-form';

/**
 * Componente de campo de entrada de texto para letras e caracteres especiais
 * @param {Object} props - Propriedades do componente
 * @param {string} props.name - Nome do campo (usado para register e id)
 * @param {string} props.label - Texto do label do campo
 * @param {string} props.accessKey - Tecla de acesso rápido
 * @param {string} props.placeholder - Texto de placeholder
 * @param {string|number} props.maxLength - Comprimento máximo do campo
 * @param {string|number} props.minLength - Comprimento mínimo do campo
 * @param {boolean} props.required - Se o campo é obrigatório
 * @param {boolean} props.readOnly - Se o campo é somente leitura
 * @param {boolean} props.disabled - Se o campo está desabilitado
 * @param {string|number} props.tabIndex - Índice de tabulação
 * @param {string} props.pattern - Padrão de validação
 * @param {string} props.title - Tooltip do campo
 * @param {string} props.errorMessage - Mensagem de erro personalizada
 * @param {Array} props.dataListOptions - Opções para o datalist
 * @param {boolean} props.autoFocus - Se o campo deve receber foco automático
 * @param {string} props.tipoTexto - Tipo de texto ("Nome", "Endereco", "Livre")
 * @returns {JSX.Element} Componente de campo de texto
 */
const HformInputTextLetters = ({
    name,
    label,
    accessKey,
    placeholder,
    maxLength = "100",
    minLength = "1",
    required = false,
    readOnly = false,
    disabled = false,
    tabIndex,
    // pattern,
    title,
    errorMessage,
    dataListOptions = [],
    autoFocus = false,
    tipoTexto = "Livre" // Valor padrão
}) => {
    // Definições padrão baseadas no tipo de texto
    const definicoesTexto = {
        Livre: {
            pattern: "[A-Za-zÀ-ÖØ-öø-ÿ\\s,.-\"]+", // Corrigidos os caracteres de escape desnecessários
            title: "Campo para texto livre com letras, espaços e caracteres especiais",
            errorMessage: "Apenas letras, espaços e caracteres especiais são permitidos",
            placeholder: "Digite o texto"
        },
        Nome: {
            pattern: "[A-Za-zÀ-ÖØ-öø-ÿ\\s,.-]+", // Corrigidos os caracteres de escape desnecessários
            title: "Campo para nome com letras, espaços e caracteres especiais",
            errorMessage: "Apenas letras, espaços, apóstrofos, vírgulas e pontos são permitidos",
            placeholder: "Digite o nome (ex: d'Avilla da Silva)"
        },
        Endereco: {
            pattern: "[A-Za-zÀ-ÖØ-öø-ÿ0-9\\s,.-°/]+", // Corrigidos os caracteres de escape desnecessários
            title: "Campo para endereço com letras, números, espaços e caracteres especiais",
            errorMessage: "Formato de endereço inválido",
            placeholder: "Digite o endereço (ex: Rua Nome, 123 - Bairro, Cidade)"
        }
    };

    // Obtém os métodos do react-hook-form do contexto do formulário
    const methods = useFormContext();

    // Verificação de segurança para garantir que o contexto do formulário exista
    if (!methods) {
        console.error('HformInputTextLetters deve ser usado dentro de um FormProvider');
        return (
            <div className="form-group mb-3">
                <div className="alert alert-danger">
                    Erro: FormProvider não encontrado. Este componente deve ser usado dentro de um FormProvider.
                </div>
            </div>
        );
    }

    const {
        register,
        formState: { errors },
        watch,
    } = methods;

    // Define os valores padrão com base no tipo de texto
    const textConfig = definicoesTexto[tipoTexto] || definicoesTexto.Livre;

    // Usa os valores fornecidos ou os valores padrão do tipo de texto
    const textTitle = title || textConfig.title;
    const textErrorMessage = errorMessage || textConfig.errorMessage;
    const textPlaceholder = placeholder || textConfig.placeholder;

    // Funções para registrar logs
    const handleFocus = (fieldName, value) => {
        console.log('onFocus:', fieldName, value);
    };

    const handleChange = (fieldName, value) => {
        console.log('onChange:', fieldName, value);
    };

    const handleBlur = (fieldName, value) => {
        console.log('onBlur:', fieldName, value);
    };

    // Função de validação customizada para permitir apóstrofos sem incluí-los no pattern do HTML
    const validateText = (value) => {
        if (!value) return true;
        
        // Expressões regulares para validação com React Hook Form (podem incluir apóstrofo)
        // Corrigidos os caracteres de escape desnecessários
        const regexLivre = /^[A-Za-zÀ-ÖØ-öø-ÿ\s,.\-'"]+$/;
        const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ\s,.\-']+$/;
        const regexEndereco = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s,.\-'°/]+$/;
        
        // Verifica o tipo de texto e aplica a regex apropriada
        switch (tipoTexto) {
            case 'Nome':
                return regexNome.test(value) || textErrorMessage;
            case 'Endereco':
                return regexEndereco.test(value) || textErrorMessage;
            case 'Livre':
            default:
                return regexLivre.test(value) || textErrorMessage;
        }
    };

    // Configuração do registro do campo
    const fieldRegister = register(name, {
        required: required ? 'Este campo é obrigatório' : false,
        validate: validateText, // Usa validação customizada em vez de pattern
        onBlur: (e) => handleBlur(name, e.target.value),
        onChange: (e) => handleChange(name, e.target.value),
    });

    // ID do datalist
    const datalistId = `${name}-list`;

    // Valor atual do campo
    const currentValue = watch(name) || '';

    return (
        <div className="form-group mb-3">
            {label && (
                <label htmlFor={name} className="form-label">
                    {label}
                </label>
            )}
            <input
                type="text"
                className={`form-control ${errors[name] ? 'is-invalid' : ''}`}
                {...fieldRegister}
                onFocus={() => handleFocus(name, currentValue)}
                accessKey={accessKey}
                autoComplete="off"
                autoFocus={autoFocus}
                contentEditable="true"
                dir="ltr"
                dirname={`${name}.dir`}
                draggable="false"
                form="hook-form"
                id={name}
                lang="pt-BR"
                list={dataListOptions?.length > 0 ? datalistId : undefined}
                maxLength={maxLength}
                minLength={minLength}
                name={name}
                placeholder={textPlaceholder}
                required={required}
                readOnly={readOnly}
                disabled={disabled}
                size="40"
                spellCheck="true"
                tabIndex={tabIndex}
                title={textTitle}
                translate="yes"
                value={currentValue}
            />

            {dataListOptions?.length > 0 && (
                <datalist id={datalistId}>
                    {dataListOptions.map((option, index) => (
                        <option key={index} value={option} />
                    ))}
                </datalist>
            )}

            {errors[name] && (
                <div className="invalid-feedback">
                    {errors[name].message}
                </div>
            )}
        </div>
    );
};

export default HformInputTextLetters;