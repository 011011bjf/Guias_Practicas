using System.Text;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.AspNetCore.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Microsoft.IdentityModel.Tokens;
using Scalar.AspNetCore;

var builder = WebApplication.CreateBuilder(args);

// DbContext PostgreSQL
builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseNpgsql(
        builder.Configuration.GetConnectionString("Postgres"),
        npg => npg.SetPostgresVersion(16, 0) // Guia mentions 18, but previous was 16. I'll keep 16.
    )
);

// Servicios
builder.Services.AddScoped<IEstudianteService, EstudianteService>();

// CORS
builder.Services.AddCors(o => o.AddPolicy("frontend", p => 
    p.WithOrigins("http://localhost:4200")
     .AllowAnyHeader()
     .AllowAnyMethod()));

// Autenticación JWT
var jwtClave = builder.Configuration["Jwt:Clave"] ?? "12345678901234567890123456789012";
builder.Services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme)
    .AddJwtBearer(opt =>
    {
        opt.TokenValidationParameters = new TokenValidationParameters
        {
            ValidateIssuer = true,
            ValidateAudience = true,
            ValidateLifetime = true,
            ValidateIssuerSigningKey = true,
            ValidIssuer = "uteq-appweb",
            ValidAudience = "uteq-estudiantes",
            IssuerSigningKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(jwtClave))
        };
    });

builder.Services.AddAuthorization();

// OpenAPI y Controladores
builder.Services.AddOpenApi();
builder.Services.AddControllers();

var app = builder.Build();

// Manejo global de excepciones (ProblemDetails)
app.UseExceptionHandler(exceptionHandlerApp =>
{
    exceptionHandlerApp.Run(async context =>
    {
        context.Response.StatusCode = StatusCodes.Status500InternalServerError;
        var exceptionHandlerPathFeature = context.Features.Get<IExceptionHandlerPathFeature>();
        
        await context.Response.WriteAsJsonAsync(new ProblemDetails
        {
            Status = StatusCodes.Status500InternalServerError,
            Title = "An error occurred while processing your request.",
            Detail = exceptionHandlerPathFeature?.Error.Message
        });
    });
});

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
    app.MapScalarApiReference();
}

app.UseHttpsRedirection();
app.UseCors("frontend");
app.UseAuthentication();
app.UseAuthorization();

// Mapear endpoints y controladores
app.MapControllers();
app.MapBasicosEndpoints();
app.MapEstudiantesEndpoints();
app.MapAuthEndpoints(builder.Configuration);

// Endpoints de Carreras
var grupoCarreras = app.MapGroup("/v1/carreras").RequireAuthorization();
grupoCarreras.MapGet("/", async (AppDbContext db) => await db.Carreras.ToListAsync());
grupoCarreras.MapPost("/", async (CarreraEntradaDto dto, AppDbContext db) => {
    var c = new Carrera { Nombre = dto.Nombre };
    db.Carreras.Add(c);
    await db.SaveChangesAsync();
    return Results.Created($"/v1/carreras/{c.Id}", new CarreraSalidaDto(c.Id, c.Nombre));
});

app.Run();
