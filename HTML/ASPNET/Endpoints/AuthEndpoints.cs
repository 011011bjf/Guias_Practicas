using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using Microsoft.IdentityModel.Tokens;

public static class AuthEndpoints
{
    public static void MapAuthEndpoints(this IEndpointRouteBuilder routes, IConfiguration cfg)
    {
        var grupo = routes.MapGroup("/v1/auth");

        grupo.MapPost("/login", (LoginDto dto) =>
        {
            // Ejercicio 14.4
            if ((dto.Usuario != "admin" && dto.Usuario != "consulta") || dto.Clave != "secreto")
                return Results.Unauthorized();

            var clave = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(cfg["Jwt:Clave"] ?? "12345678901234567890123456789012"));
            var credenciales = new SigningCredentials(clave, SecurityAlgorithms.HmacSha256);

            var claims = new[]
            {
                new Claim(ClaimTypes.Name, dto.Usuario),
                new Claim(ClaimTypes.Role, dto.Usuario == "admin" ? "admin" : "consulta")
            };

            var token = new JwtSecurityToken(
                issuer: "uteq-appweb",
                audience: "uteq-estudiantes",
                claims: claims,
                expires: DateTime.UtcNow.AddHours(2),
                signingCredentials: credenciales);

            var jwt = new JwtSecurityTokenHandler().WriteToken(token);
            return Results.Ok(new { token = jwt });
        });
    }
}
