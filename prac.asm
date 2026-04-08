section .data
    prompt db "Enter score (0-100): "
    prompt_len equ $ - prompt

    excellent db "Excellent", 10
    excellent_len equ $ - excellent

    pass db "Pass", 10
    pass_len equ $ - pass

    fail db "Fail", 10
    fail_len equ $ - fail

section .bss
    input resb 4        ; buffer for input

section .text
    global _start

_start:

    ; print prompt
    mov rax, 1
    mov rdi, 1
    mov rsi, prompt
    mov rdx, prompt_len
    syscall

    ; read input
    mov rax, 0
    mov rdi, 0
    mov rsi, input
    mov rdx, 4
    syscall

    ; convert ASCII to integer
    mov rsi, input
    xor rax, rax        ; result = 0

convert:
    mov bl, [rsi]
    cmp bl, 10          ; newline
    je done_convert

    sub bl, '0'
    imul rax, rax, 10
    add rax, rbx

    inc rsi
    jmp convert

done_convert:

    ; rax now contains score

    cmp rax, 90
    jge print_excellent

    cmp rax, 75
    jge print_pass

    jmp print_fail

print_excellent:
    mov rax, 1
    mov rdi, 1
    mov rsi, excellent
    mov rdx, excellent_len
    syscall
    jmp exit

print_pass:
    mov rax, 1
    mov rdi, 1
    mov rsi, pass
    mov rdx, pass_len
    syscall
    jmp exit

print_fail:
    mov rax, 1
    mov rdi, 1
    mov rsi, fail
    mov rdx, fail_len
    syscall

exit:
    mov rax, 60
    xor rdi, rdi
    syscall
