using AutoMapper;
using FermaOrders.API.Controllers.Response;
using FermaOrders.Application.DTOs.Auth;
using FermaOrders.Application.Interface.Auth;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using System.Net;
using System.Web.Http;
using FromBodyAttribute = Microsoft.AspNetCore.Mvc.FromBodyAttribute;
using HttpPostAttribute = Microsoft.AspNetCore.Mvc.HttpPostAttribute;
using RouteAttribute = Microsoft.AspNetCore.Mvc.RouteAttribute;

namespace FermaOrders.API.Controllers.Auth
{
    [Route("api/[controller]")]
    [ApiController]
    public class UserController : ControllerBase
    {
        private readonly IWebHostEnvironment _env;
        private readonly IUsuarioService _usuarioService;
        private readonly IMapper _mapper;
        protected RespuestaAPI _respuestaAPI;

        public UserController(IUsuarioService usuarioService, IMapper mapper, IWebHostEnvironment env)
        {
            _usuarioService = usuarioService;
            _mapper = mapper;
            _respuestaAPI = new();
            _env = env;
        }

        [AllowAnonymous]
        [HttpPost("Registro")]
        [ProducesResponseType(StatusCodes.Status201Created)]
        [ProducesResponseType(StatusCodes.Status400BadRequest)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> Registro([FromForm] UsuarioRegistroDto usuarioRegistroDto)
        {

            if (!ModelState.IsValid)
            {
                _respuestaAPI.StatusCode = HttpStatusCode.BadRequest;
                _respuestaAPI.IsSuccess = false;
                _respuestaAPI.ErrorMessages.Add("Datos de registro inválidos");
                return BadRequest(_respuestaAPI);
            }


            if (!_usuarioService.IsUniqueUser(usuarioRegistroDto.NombreUsuario))
            {
                _respuestaAPI.StatusCode = HttpStatusCode.BadRequest;
                _respuestaAPI.IsSuccess = false;
                _respuestaAPI.ErrorMessages.Add("El nombre de usuario ya existe");
                return BadRequest(_respuestaAPI);
            }

            var usuarioCreado = await _usuarioService.RegistroAsync(usuarioRegistroDto);
            if (usuarioCreado == null)
            {
                _respuestaAPI.StatusCode = HttpStatusCode.InternalServerError;
                _respuestaAPI.IsSuccess = false;
                _respuestaAPI.ErrorMessages.Add("Error en el registro de usuario");
                return StatusCode(StatusCodes.Status500InternalServerError, _respuestaAPI);
            }


            _respuestaAPI.StatusCode = HttpStatusCode.Created;
            _respuestaAPI.IsSuccess = true;
            _respuestaAPI.Result = usuarioCreado;
            return CreatedAtAction(nameof(Registro), new { id = usuarioCreado.Id }, _respuestaAPI);
        }


        [AllowAnonymous]
        [HttpPost("Login")]
        [ProducesResponseType(StatusCodes.Status201Created)]
        [ProducesResponseType(StatusCodes.Status400BadRequest)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> Login([FromForm] UsuarioLoginDto usuarioLoginDto)
        {
            var respuestaLogin = await _usuarioService.LoginAsync(usuarioLoginDto);

            if (respuestaLogin == null || string.IsNullOrEmpty(respuestaLogin.Token))
            {
                _respuestaAPI.StatusCode = HttpStatusCode.BadRequest;
                _respuestaAPI.IsSuccess = false;
                _respuestaAPI.ErrorMessages.Add("El nombre de Usuario o Password son Incorrectos");
                return BadRequest(_respuestaAPI);
            }

            _respuestaAPI.StatusCode = HttpStatusCode.OK;
            _respuestaAPI.IsSuccess = true;
            ///Usamos esto para que nos devuelva el login
            _respuestaAPI.Result = respuestaLogin;
            return Ok(_respuestaAPI);
        }

        [Authorize]
        [HttpPost("logout")]
        public async Task<IActionResult> Logout()
        {
            var token = Request.Headers["Authorization"].ToString().Replace("Bearer ", "");

            await _usuarioService.LogoutAsync(token);

            return Ok(new { message = "Sesión cerrada correctamente." });
        }
    }
}
