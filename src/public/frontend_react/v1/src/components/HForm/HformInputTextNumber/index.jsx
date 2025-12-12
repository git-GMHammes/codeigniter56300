// C:\laragon\www\hform\src\public\script\react_modelo_v3\frontend\src\components\HForm\HformInputTextNumber\index.jsx
import React from 'react';
import { useFormContext } from 'react-hook-form';

/**
 * Componente de campo de entrada para números, traços, pontos e vírgulas
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
 * @param {string} props.tipoNumero - Tipo de número ("Inteiro", "Decimal", "Monetario")
 * @returns {JSX.Element} Componente de campo de número
 */
const HformInputTextNumber = ({
    name,
    label,
    accessKey,
    placeholder,
    maxLength = "50",
    minLength = "1",
    required = false,
    readOnly = false,
    disabled = false,
    tabIndex,
    pattern,
    title,
    errorMessage,
    dataListOptions = [],
    autoFocus = false,
    tipoNumero = "Decimal" // Valor padrão
}) => {
    // Definições padrão baseadas no tipo de número
    const definicoesNumero = {
        Inteiro: {
            pattern: "[0-9\\-]+", // Hífen escapado para evitar ambiguidade
            title: "Campo para números inteiros e traços",
            errorMessage: "Apenas números inteiros e traços são permitidos",
            placeholder: "Ex: 123-456"
        },
        Decimal: {
            pattern: "[0-9\\.,\\-]+", // Hífen, ponto e vírgula escapados
            title: "Campo para números, traços, pontos e vírgulas",
            errorMessage: "Apenas números, traços, pontos e vírgulas são permitidos",
            placeholder: "Ex: 123.456-789,00"
        },
        Monetario: {
            pattern: "[0-9\\.,]+", // Ponto e vírgula escapados
            title: "Campo para valores monetários",
            errorMessage: "Apenas números, pontos e vírgulas são permitidos",
            placeholder: "Ex: 1.234,56"
        }
    };

    // Obtém os métodos do react-hook-form do contexto do formulário
    const methods = useFormContext();

    // Verificação de segurança para garantir que o contexto do formulário exista
    if (!methods) {
        console.error('HformInputTextNumber deve ser usado dentro de um FormProvider');
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

    // Define os valores padrão com base no tipo de número
    const numberConfig = definicoesNumero[tipoNumero] || definicoesNumero.Decimal;

    // Usa os valores fornecidos ou os valores padrão do tipo de número
    const numberPattern = pattern || numberConfig.pattern;
    const numberTitle = title || numberConfig.title;
    const numberErrorMessage = errorMessage || numberConfig.errorMessage;
    const numberPlaceholder = placeholder || numberConfig.placeholder;

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

    // Função de validação customizada para evitar problemas com expressões regulares no HTML
    const validateNumber = (value) => {
        if (!value) return true;

        // Expressões regulares para validação com React Hook Form
        const regexInteiro = /^[0-9\-]+$/;
        const regexDecimal = /^[0-9\.,\-]+$/;
        const regexMonetario = /^[0-9\.,]+$/;

        // Verifica o tipo de número e aplica a regex apropriada
        switch (tipoNumero) {
            case 'Inteiro':
                return regexInteiro.test(value) || numberErrorMessage;
            case 'Decimal':
                return regexDecimal.test(value) || numberErrorMessage;
            case 'Monetario':
                return regexMonetario.test(value) || numberErrorMessage;
            default:
                return regexDecimal.test(value) || numberErrorMessage;
        }
    };

    // Configuração do registro do campo
    const fieldRegister = register(name, {
        required: required ? 'Este campo é obrigatório' : false,
        validate: validateNumber, // Usar validação customizada em vez de pattern
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
                placeholder={numberPlaceholder}
                required={required}
                readOnly={readOnly}
                disabled={disabled}
                size="40"
                spellCheck="false"
                tabIndex={tabIndex}
                title={numberTitle}
                translate="no"
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

export default HformInputTextNumber;