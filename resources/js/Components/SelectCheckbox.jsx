import React from "react";

const SelectCheckbox = ({
    id,
    label,
    options,
    valueKey,
    labelKey,
    selectedValues,
    onChange,
    error,
    mustFill,
    disabled,
}) => {
    return (
        <div className="mb-3">
            {selectedValues ? (
                <>
                    <label className="form-label fw-bold" htmlFor={id}>
                        {label}
                        {mustFill && <span className="text-danger">*</span>}
                    </label>
                    <br />
                    {options.map((option, index) => (
                        <div
                            className="form-check form-check-inline"
                            key={index}
                        >
                            <input
                                className="form-check-input"
                                type="checkbox"
                                value={option[valueKey]}
                                onChange={onChange}
                                id={`check-${id}-${option[valueKey]}`}
                                checked={selectedValues.includes(
                                    option[valueKey]
                                )}
                                disabled={disabled}
                            />
                            <label
                                className="form-check-label"
                                htmlFor={`check-${id}-${option[valueKey]}`}
                            >
                                {option[labelKey]}
                            </label>
                        </div>
                    ))}
                </>
            ) : (
                <>
                    <label className="form-label fw-bold" htmlFor={id}>
                        {label}
                        {mustFill && <span className="text-danger">*</span>}
                    </label>
                    <br />
                    {options.map((option, index) => (
                        <div
                            className="form-check form-check-inline"
                            key={index}
                        >
                            <input
                                className="form-check-input"
                                type="checkbox"
                                value={option[valueKey]}
                                onChange={onChange}
                                id={`check-${id}-${option[valueKey]}`}
                                disabled={disabled}
                            />
                            <label
                                className="form-check-label"
                                htmlFor={`check-${id}-${option[valueKey]}`}
                            >
                                {option[labelKey]}
                            </label>
                        </div>
                    ))}
                </>
            )}
            {error && <div className="alert alert-danger">{error}</div>}
        </div>
    );
};

export default SelectCheckbox;
