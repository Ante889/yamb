import { ChangeEvent } from "react";

export function AuthInput({
                              type = "text",
                              value,
                              onChange,
                              placeholder,
                          }: {
    type?: string;
    value: string;
    onChange: (e: ChangeEvent<HTMLInputElement>) => void;
    placeholder: string;
}) {
    return (
        <input
            style={{marginTop: '5px'}}
            type={type}
            value={value}
            onChange={onChange}
            placeholder={placeholder}
        />
    );
}
