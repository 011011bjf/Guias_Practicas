public static class BasicosEndpoints
{
    public static void MapBasicosEndpoints(this IEndpointRouteBuilder routes)
    {
        var grupo = routes.MapGroup("/basicos");

        // 13.1.1 Endpoint de versión
        grupo.MapGet("/version", () => Results.Ok(new { api = "estudiantes", version = "1.0" }));

        // 13.1.2 Par o impar
        grupo.MapGet("/par/{n:int}", (int n) => Results.Ok(new { numero = n, esPar = n % 2 == 0 }));

        // 13.1.3 Fecha actual UTC en formato ISO
        grupo.MapGet("/fecha", () => Results.Ok(new { fecha = DateTime.UtcNow.ToString("O") }));

        // 4.5.1 Saludo con nombre en query o ruta
        grupo.MapGet("/saludo", (string? nombre) => 
            Results.Ok(new { mensaje = $"Hola, {nombre ?? "desconocido"}!" }));

        // 4.5.2 Hora actual
        grupo.MapGet("/hora", () => Results.Ok(new { hora = DateTime.Now.ToString("HH:mm:ss") }));

        // 4.5.3 Suma
        grupo.MapGet("/suma/{a:int}/{b:int}", (int a, int b) => Results.Ok(new { a, b, suma = a + b }));

        // 6.4.1 Ping
        grupo.MapGet("/ping", () => Results.Ok(new { estado = "ok" }));

        // 6.4.2 Eco
        grupo.MapPost("/eco", (EcoDto dto) => Results.Ok(new { mensaje = dto.Mensaje, hora = DateTime.UtcNow }));

        // 13.1.6 Mayúscula
        grupo.MapGet("/mayuscula/{texto}", (string texto) => Results.Ok(new { original = texto, mayuscula = texto.ToUpper() }));
    }
}

public record EcoDto(string Mensaje);
