<h1 align="center">How to run Windows™ 95<sup>©</sup> on the web</h1>

## Setup:
* Download and install [dosbox](https://sourceforge.net/projects/dosbox/files/latest/download) to _C:\dosbox_
* Download [622C](http://www.rloe.com/randytheracer/622c.zip) and extract it into _C:\dosbox_
* Download and install [bochs](https://sourceforge.net/projects/bochs/files/latest/download) to _C:\bochs_
* Download and install [OSFMount](https://www.osforensics.com/downloads/osfmount.exe)
* Download and install [7-zip](https://www.7-zip.org/a/7z1900-x64.exe)
* Download the [windows 95 installation](https://winworldpc.com/download/4120c593-e280-9818-c39a-11c3a4e284a2/from/c39ac2af-c381-c2bf-1b25-11c3a4e284a2) and extract it to _C:\windows95_ using 7-zip
## Steps:
1. Copy _C:\bochs\bximage.exe_ into _C:\dosbox_ and run it, in the prompt press 1, and leave all the values as default (just press enter) except the hard disk size in megabytes which should be changed to 400
2. Run _C:\dosbox\DOSBox.exe_ and in it run:
```bat
imgmount 2 c.img -size 512,63,16,812 -t hdd -fs none
boot 622c.img
fdisk
1
1
Y
```
3. When you see another prompt just press Enter to close DOSBox, then, open _C:\dosbox\DOSBox.exe_ again and run:
```bat
imgmount c c.img
boot 622c.img
format c:
y
win95
```
4. Close DOSBox, then, extract the content of all the image files in _C:\windows95_ (except _Boot.img_) into a new folder named _C:\win95_ (extract the image files themself by opening them in 7-zip)
5. Open OSFMount and choose _Mount new..._ you need to find the image disk (_C:\dosbox\c.img_) then choose _Mount all partitions_ option and uncheck the _read-only_ option, then press _ok_, now if you open the file explorer you will see a new drive (D: in my case), drag&drop the _C:\win95_ folder into the new drive, then in OSFMount click on the new drive and then on _Dismount_
6. Open _C:\dosbox\DOSBox.exe_ and run:
```bat
imgmount c c.img
boot 622c.img
mouse.com
c:
cd win95
setup /is
```
7. Close DOSBox, then from _C:\windows95\Boot.img_ extract _AUTOEXEC.BAT_ to a **new** folder named _win_ using 7-zip and place _C:\dosbox\c.img_ in the _win_ folder, finally, download [configuration](https://gist.github.com/donno2048/e9192c9030e37c18213b1c560eda7f80/archive/1141069f18b32a67df7ee164fc476b8ae79c61f4.zip) and unzip the _dosbox.conf_ in it into the _win_ folder
8. Open bash command line (you might need to install it, you can follow [Chris Hoffman's guide](https://www.howtogeek.com/249966)) in the _win_ folder and run:
```bash
sudo apt-get update
yes | sudo apt-get install python2.7 git binutils cmake automake
cd ..
git clone https://github.com/warpcoil/em-dosbox
git clone https://github.com/emscripten-core/emsdk
mv win em-dosbox/src
cd emsdk
sudo autoreconf -f -i
./emsdk install latest
./emsdk activate latest
source ./emsdk_env.sh
cd ../em-dosbox
./autogen.sh
emconfigure ./configure --host=none-none-none PPFLAGS="-s USE_SDL=2 -s USE_SDL_NET=2" LDFLAGS="-s USE_SDL_NET=2"
make
cd src
python packager.py win95 win AUTOEXEC.BAT
```
