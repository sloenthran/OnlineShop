#include <amxmodx>
#include <amxmisc>
#include <sockets>

#pragma semicolon 1

new Sockets;
new Handle:MySQL;
new MaxPlayers = 0;

public plugin_init() 
{
	register_plugin("Online Shop", "0.1", "Sloenthran")
	
	register_clcmd("say /sklepsms", "GlobalMenu");
	register_clcmd("say_team /sklepsms", "GlobalMenu");
	register_clcmd("say !sklepsms", "GlobalMenu");
	register_clcmd("say_team !sklepsms", "GlobalMenu");
	
	CvarHost = register_cvar("onlineshop_host", "", FCVAR_PROTECTED|FCVAR_SPONLY);
	CvarUser = register_cvar("onlineshop_user", "", FCVAR_PROTECTED|FCVAR_SPONLY);
	CvarPass = register_cvar("onlineshop_pass", "", FCVAR_PROTECTED|FCVAR_SPONLY);
	CvarBase = register_cvar("onlineshop_base", "", FCVAR_PROTECTED|FCVAR_SPONLY);
	
	CvarWWW = register_cvar("onlineshop_www", "sklep.myserv.pl", FCVAR_PROTECTED|FCVAR_SPONLY);
	
	set_task(0.5, "PrepareSQL");
}

public plugin_cfg()
{
	
	MaxPlayers = get_maxplayers();
	
}

public PrepareSQL()
{
	new ValueHost[32], ValueUser[32], ValuePass[32], ValueBase[32];
	
	get_pcvar_string(CvarHost, ValueHost, sizeof(ValueHost));
	get_pcvar_string(CvarUser, ValueUser, sizeof(ValueUser));
	get_pcvar_string(CvarPass, ValuePass, sizeof(ValuePass));
	get_pcvar_string(CvarBase, ValueBase, sizeof(ValueBase));
	
}

public test(id){
	new iError,szSendBuffer[512]
	
	Sockets = socket_open(HOST, 80, SOCKET_TCP, iError)
	
	switch (iError) 
	{ 
		case 1: 
		{ 
			log_amx("Unable to create socket.") 
			return ;
		} 
		case 2: 
		{ 
			log_amx("Unable to connect to hostname.") 
			return ;
		} 
		case 3: 
		{ 
			log_amx("Unable to connect to the HTTP port.") 
			return ;
		} 
	} 
	
	format(szSendBuffer, charsmax(szSendBuffer), "GET %s^nHost:%s^r^n^r^n", SITE, HOST) 
	socket_send(Sockets, szSendBuffer, charsmax(szSendBuffer)) 
	
	set_task(1.0, "socketAnswer", .flags = "b") 
}

public socketAnswer(){
	if (socket_change(Sockets)) { 
		new szData[1024]
		
		socket_recv(Sockets, szData, charsmax(szData) ) 
		
		log_amx(szData);
		
		socket_close(Sockets);

		remove_task( 0 );
	}
}