section .data
    ask_scale     db "Choose scale to convert from (F or C): "
    ask_scale_len equ $ - ask_scale

    ask_temp_f    db "Enter Fahrenheit value: "
    ask_temp_f_len equ $ - ask_temp_f

    ask_temp_c    db "Enter Celsius value: "
    ask_temp_c_len equ $ - ask_temp_c

    out_f         db "Converted temperature in Fahrenheit: "
    out_f_len     equ $ - out_f

    out_c         db "Converted temperature in Celsius: "
    out_c_len     equ $ - out_c

    invalid_msg   db "Invalid choice.", 10
    invalid_len   equ $ - invalid_msg

    newline       db 10

section .bss
    input   resb 32
    numbuf  resb 32

section .text
    global _start

_start:
    ; Ask user for scale
    mov rax, 1
    mov rdi, 1
    mov rsi, ask_scale
    mov rdx, ask_scale_len
    syscall

    ; Read scale choice
    mov rax, 0
    mov rdi, 0
    mov rsi, input
    mov rdx, 32
    syscall

    mov al, [input]

    cmp al, 'F'
    je fahrenheit_to_celsius
    cmp al, 'f'
    je fahrenheit_to_celsius

    cmp al, 'C'
    je celsius_to_fahrenheit
    cmp al, 'c'
    je celsius_to_fahrenheit

    ; Invalid choice
    mov rax, 1
    mov rdi, 1
    mov rsi, invalid_msg
    mov rdx, invalid_len
    syscall
    jmp exit_program

fahrenheit_to_celsius:
    ; Ask for Fahrenheit value
    mov rax, 1
    mov rdi, 1
    mov rsi, ask_temp_f
    mov rdx, ask_temp_f_len
    syscall

    ; Read value
    mov rax, 0
    mov rdi, 0
    mov rsi, input
    mov rdx, 32
    syscall

    mov rdi, input
    call atoi
    ; rax = Fahrenheit value

    ; C = (F - 32) * 5 / 9
    sub rax, 32
    imul rax, rax, 5
    cqo
    mov rbx, 9
    idiv rbx
    ; rax = Celsius result

    mov rbx, rax

    ; Print label
    mov rax, 1
    mov rdi, 1
    mov rsi, out_c
    mov rdx, out_c_len
    syscall

    ; Print number
    mov rax, rbx
    call itoa
    ; rsi = pointer, rdx = length
    mov rax, 1
    mov rdi, 1
    syscall

    ; Print newline
    mov rax, 1
    mov rdi, 1
    mov rsi, newline
    mov rdx, 1
    syscall

    jmp exit_program

celsius_to_fahrenheit:
    ; Ask for Celsius value
    mov rax, 1
    mov rdi, 1
    mov rsi, ask_temp_c
    mov rdx, ask_temp_c_len
    syscall

    ; Read value
    mov rax, 0
    mov rdi, 0
    mov rsi, input
    mov rdx, 32
    syscall

    mov rdi, input
    call atoi
    ; rax = Celsius value

    ; F = (C * 9 / 5) + 32
    imul rax, rax, 9
    cqo
    mov rbx, 5
    idiv rbx
    add rax, 32
    ; rax = Fahrenheit result

    mov rbx, rax

    ; Print label
    mov rax, 1
    mov rdi, 1
    mov rsi, out_f
    mov rdx, out_f_len
    syscall

    ; Print number
    mov rax, rbx
    call itoa
    mov rax, 1
    mov rdi, 1
    syscall

    ; Print newline
    mov rax, 1
    mov rdi, 1
    mov rsi, newline
    mov rdx, 1
    syscall

exit_program:
    mov rax, 60
    xor rdi, rdi
    syscall

; ---------------------------
; atoi: ASCII string -> integer
; input:  rdi = pointer to string
; output: rax = integer
; ---------------------------
atoi:
    xor rax, rax
    xor r8, r8          ; sign flag = 0

    cmp byte [rdi], '-'
    jne .loop_start
    mov r8, 1
    inc rdi

.loop_start:
    mov bl, [rdi]
    cmp bl, 10
    je .done
    cmp bl, 0
    je .done
    cmp bl, '0'
    jb .done
    cmp bl, '9'
    ja .done

    imul rax, rax, 10
    sub bl, '0'
    movzx rbx, bl
    add rax, rbx

    inc rdi
    jmp .loop_start

.done:
    cmp r8, 1
    jne .return
    neg rax

.return:
    ret

; ---------------------------
; itoa: integer -> ASCII string
; input:  rax = integer
; output: rsi = pointer to string
;         rdx = length
; ---------------------------
itoa:
    lea rdi, [numbuf + 31]
    mov byte [rdi], 0
    xor rcx, rcx

    xor r8, r8          ; sign flag
    cmp rax, 0
    jge .convert
    neg rax
    mov r8, 1

.convert:
    mov rbx, 10

.repeat:
    xor rdx, rdx
    div rbx
    add dl, '0'
    dec rdi
    mov [rdi], dl
    inc rcx
    test rax, rax
    jnz .repeat

    cmp r8, 1
    jne .done
    dec rdi
    mov byte [rdi], '-'
    inc rcx

.done:
    mov rsi, rdi
    mov rdx, rcx
    ret