import React from "react";

const AuthField = ({ label, type, value, onChange, placeholder, error, icon, fieldselect, required, options, disabled }) => {
  return (
      <div className="mb-3">
          {fieldselect ? (
              <div>
                  <label className="mb-1 fw-bold">{label}</label>
                  <div className="input-group mb-3">
                      <span className="input-group-text">
                          <i className={icon}></i>
                      </span>
                      <select
                          type="text"
                          onChange={onChange}
                          value={value}
                          className="form-select"
                          required={required}
                      >
                          <option disabled value="">
                              Select {label}
                          </option>
                          {options.map((option) => (
                              <option key={option} value={option}>
                                  {option}
                              </option>
                          ))}
                      </select>
                  </div>
                  {error && <div className="alert alert-danger">{error}</div>}
              </div>
          ) : (
              <div>
                  <label className="mb-1 fw-bold">{label}</label>
                  <div className="input-group mb-3">
                      <span className="input-group-text">
                          <i className={icon}></i>
                      </span>
                      <input
                          type={type}
                          className="form-control"
                          value={value}
                          onChange={onChange}
                          placeholder={placeholder}
                          required={required}
                          disabled={disabled}
                      />
                  </div>
                  {error && <div className="alert alert-danger">{error}</div>}
              </div>
          )}
      </div>
  );
};

export default AuthField;