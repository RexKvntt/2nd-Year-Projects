section .data
    mode_msg db "Select mode: [1] Regular [2] Human Body [3] Weather: "
    mode_len equ $ - mode_msg

    ask_scale db "Choose scale to convert from (F or C): "
    ask_scale_len equ $ - ask_scale

    ask_f db "Enter Fahrenheit value: "
    ask_f_len equ $ - ask_f

    ask_c db "Enter Celsius value: "
    ask_c_len equ $ - ask_c

    out_c db "Celsius: "
    out_c_len equ $ - out_c

    out_f db "Fahrenheit: "
    out_f_len equ $ - out_f

    ; Celsius condition messages
    c_hypo db "Condition: Hypothermia (dangerously low).",10
    c_hypo_len equ $ - c_hypo

    c_mildlow db "Condition: Below normal (possible mild hypothermia).",10
    c_mildlow_len equ $ - c_mildlow

    c_normal db "Condition: Normal and stable.",10
    c_normal_len equ $ - c_normal

    c_mildfever db "Condition: Mild fever (possible cold/infection).",10
    c_mildfever_len equ $ - c_mildfever

    c_highfever db "Condition: High fever.",10
    c_highfever_len equ $ - c_highfever

    c_critical db "Condition: Critical (hyperpyrexia).",10
    c_critical_len equ $ - c_critical

    ; Fahrenheit condition messages
    f_hypo db "Condition: Hypothermia (dangerously low).",10
    f_hypo_len equ $ - f_hypo

    f_mildlow db "Condition: Below normal.",10
    f_mildlow_len equ $ - f_mildlow

    f_normal db "Condition: Normal and stable.",10
    f_normal_len equ $ - f_normal

    f_mildfever db "Condition: Mild fever.",10
    f_mildfever_len equ $ - f_mildfever

    f_highfever db "Condition: High fever.",10
    f_highfever_len equ $ - f_highfever

    f_critical db "Condition: Critical (very high fever).",10
    f_critical_len equ $ - f_critical

    ; Weather messages
    w_freezing db "Weather: Freezing / below 0C.",10
    w_freezing_len equ $ - w_freezing

    w_cold db "Weather: Cold.",10
    w_cold_len equ $ - w_cold

    w_cool db "Weather: Cool.",10
    w_cool_len equ $ - w_cool

    w_warm db "Weather: Warm.",10
    w_warm_len equ $ - w_warm

    w_hot db "Weather: Hot.",10
    w_hot_len equ $ - w_hot

    w_danger db "Weather: Dangerously hot.",10
    w_danger_len equ $ - w_danger

    again_msg db "Convert again? (y/n): "
    again_len equ $ - again_msg

    newline db 10

section .bss
    input resb 32
    numbuf resb 32
    mode resb 1

section .text
    global _start

_start:

main_loop:

    ; Ask mode
    mov rax,1
    mov rdi,1
    mov rsi,mode_msg
    mov rdx,mode_len
    syscall

    mov rax,0
    mov rdi,0
    mov rsi,input
    mov rdx,32
    syscall

    mov al,[input]
    mov [mode],al

    ; Ask scale
    mov rax,1
    mov rdi,1
    mov rsi,ask_scale
    mov rdx,ask_scale_len
    syscall

    mov rax,0
    mov rdi,0
    mov rsi,input
    mov rdx,32
    syscall

    mov al,[input]

    cmp al,'F'
    je f_to_c
    cmp al,'f'
    je f_to_c
    cmp al,'C'
    je c_to_f
    cmp al,'c'
    je c_to_f

    jmp main_loop

; -------- F → C --------
f_to_c:
    mov rax,1
    mov rdi,1
    mov rsi,ask_f
    mov rdx,ask_f_len
    syscall

    mov rax,0
    mov rdi,0
    mov rsi,input
    mov rdx,32
    syscall

    mov rdi,input
    call atoi

    sub rax,32
    imul rax,rax,5
    cqo
    mov rbx,9
    idiv rbx

    mov r12,rax

    mov rax,1
    mov rdi,1
    mov rsi,out_c
    mov rdx,out_c_len
    syscall

    mov rax,r12
    call itoa
    mov rax,1
    mov rdi,1
    syscall

    mov rax,1
    mov rdi,1
    mov rsi,newline
    mov rdx,1
    syscall

    mov al,[mode]
    cmp al,'1'
    je ask_again
    cmp al,'2'
    je celsius_conditions
    cmp al,'3'
    je weather_conditions_c
    jmp ask_again

; -------- C → F --------
c_to_f:
    mov rax,1
    mov rdi,1
    mov rsi,ask_c
    mov rdx,ask_c_len
    syscall

    mov rax,0
    mov rdi,0
    mov rsi,input
    mov rdx,32
    syscall

    mov rdi,input
    call atoi

    imul rax,rax,9
    cqo
    mov rbx,5
    idiv rbx
    add rax,32

    mov r12,rax

    mov rax,1
    mov rdi,1
    mov rsi,out_f
    mov rdx,out_f_len
    syscall

    mov rax,r12
    call itoa
    mov rax,1
    mov rdi,1
    syscall

    mov rax,1
    mov rdi,1
    mov rsi,newline
    mov rdx,1
    syscall

    mov al,[mode]
    cmp al,'1'
    je ask_again
    cmp al,'2'
    je fahrenheit_conditions
    cmp al,'3'
    je weather_conditions_f
    jmp ask_again

; -------- Medical Conditions --------
celsius_conditions:
    mov rax,r12
    cmp rax,35
    jl c_hypo_case
    cmp rax,36
    jle c_mildlow_case
    cmp rax,37
    jle c_normal_case
    cmp rax,38
    jle c_mildfever_case
    cmp rax,40
    jle c_highfever_case
    jmp c_critical_case

fahrenheit_conditions:
    mov rax,r12
    cmp rax,95
    jl f_hypo_case
    cmp rax,97
    jle f_mildlow_case
    cmp rax,99
    jle f_normal_case
    cmp rax,101
    jle f_mildfever_case
    cmp rax,104
    jle f_highfever_case
    jmp f_critical_case

; -------- Weather --------
weather_conditions_c:
    mov rax,r12
    cmp rax,0
    jl w_freezing_case
    cmp rax,10
    jle w_cold_case
    cmp rax,20
    jle w_cool_case
    cmp rax,30
    jle w_warm_case
    cmp rax,35
    jle w_hot_case
    jmp w_danger_case

weather_conditions_f:
    mov rax,r12
    cmp rax,32
    jl w_freezing_case
    cmp rax,50
    jle w_cold_case
    cmp rax,68
    jle w_cool_case
    cmp rax,86
    jle w_warm_case
    cmp rax,95
    jle w_hot_case
    jmp w_danger_case

; -------- Condition Outputs --------
c_hypo_case:
    mov rsi,c_hypo
    mov rdx,c_hypo_len
    jmp print_cond

c_mildlow_case:
    mov rsi,c_mildlow
    mov rdx,c_mildlow_len
    jmp print_cond

c_normal_case:
    mov rsi,c_normal
    mov rdx,c_normal_len
    jmp print_cond

c_mildfever_case:
    mov rsi,c_mildfever
    mov rdx,c_mildfever_len
    jmp print_cond

c_highfever_case:
    mov rsi,c_highfever
    mov rdx,c_highfever_len
    jmp print_cond

c_critical_case:
    mov rsi,c_critical
    mov rdx,c_critical_len
    jmp print_cond

f_hypo_case:
    mov rsi,f_hypo
    mov rdx,f_hypo_len
    jmp print_cond

f_mildlow_case:
    mov rsi,f_mildlow
    mov rdx,f_mildlow_len
    jmp print_cond

f_normal_case:
    mov rsi,f_normal
    mov rdx,f_normal_len
    jmp print_cond

f_mildfever_case:
    mov rsi,f_mildfever
    mov rdx,f_mildfever_len
    jmp print_cond

f_highfever_case:
    mov rsi,f_highfever
    mov rdx,f_highfever_len
    jmp print_cond

f_critical_case:
    mov rsi,f_critical
    mov rdx,f_critical_len
    jmp print_cond

w_freezing_case:
    mov rsi,w_freezing
    mov rdx,w_freezing_len
    jmp print_cond

w_cold_case:
    mov rsi,w_cold
    mov rdx,w_cold_len
    jmp print_cond

w_cool_case:
    mov rsi,w_cool
    mov rdx,w_cool_len
    jmp print_cond

w_warm_case:
    mov rsi,w_warm
    mov rdx,w_warm_len
    jmp print_cond

w_hot_case:
    mov rsi,w_hot
    mov rdx,w_hot_len
    jmp print_cond

w_danger_case:
    mov rsi,w_danger
    mov rdx,w_danger_len
    jmp print_cond

print_cond:
    mov rax,1
    mov rdi,1
    syscall
    jmp ask_again

; -------- Repeat --------
ask_again:
    mov rax,1
    mov rdi,1
    mov rsi,again_msg
    mov rdx,again_len
    syscall

    mov rax,0
    mov rdi,0
    mov rsi,input
    mov rdx,32
    syscall

    mov al,[input]
    cmp al,'y'
    je main_loop
    cmp al,'Y'
    je main_loop

    jmp exit

exit:
    mov rax,60
    xor rdi,rdi
    syscall

; -------- atoi --------
atoi:
    xor rax,rax
.next:
    mov bl,[rdi]
    cmp bl,10
    je .done
    sub bl,'0'
    imul rax,rax,10
    add rax,rbx
    inc rdi
    jmp .next
.done:
    ret

; -------- itoa --------
itoa:
    lea rdi,[numbuf+31]
    mov byte[rdi],0
    mov rcx,0
    mov rbx,10

.conv:
    xor rdx,rdx
    div rbx
    add dl,'0'
    dec rdi
    mov [rdi],dl
    inc rcx
    test rax,rax
    jnz .conv

    mov rsi,rdi
    mov rdx,rcx
    ret
